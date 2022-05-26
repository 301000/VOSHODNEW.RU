<?php

use AcyMailing\Classes\UserClass;

class SendinblueUsers extends SendinblueClass
{
    var $list;

    public function __construct(&$plugin, $headers, $list)
    {
        parent::__construct($plugin, $headers);
        $this->list = $list;
    }

    public function createUser($user)
    {
        $sendingMethod = $this->config->get('mailer_method', 'phpmail');
        if ($sendingMethod != plgAcymSendinblue::SENDING_METHOD_ID) return;

        $nameParts = explode(' ', $user->name, 2);
        $userData = [
            'email' => $user->email,
            'attributes' => [
                'LASTNAME' => empty($nameParts[1]) ? '' : $nameParts[1],
                'FIRSTNAME' => $nameParts[0],
            ],
            'updateEnabled' => true,
        ];
        $this->callApiSendingMethod('contacts', $userData, $this->headers, 'POST');
    }

    public function createFromImportedSource($source)
    {
        $userClass = new UserClass();
        $importedUsers = $userClass->getByColumnValue('source', $source);
        $this->importUsers($importedUsers);
    }

    public function importUsers($users, $ajax = false)
    {
        $sendingMethod = $this->config->get('mailer_method', 'phpmail');
        if ($sendingMethod != plgAcymSendinblue::SENDING_METHOD_ID) return;

        $response = new stdClass();
        $response->createdFolder = acym_createFolder(ACYM_TMP_FOLDER);

        if (!$response->createdFolder) {
            if ($ajax) {
                acym_sendAjaxResponse(acym_translation('ACYM_ERROR_CREATING_EXPORT_FILE'), [], false);
            } else {
                acym_enqueueMessage(acym_translation('ACYM_ERROR_CREATING_EXPORT_FILE'), 'error');
            }

            return;
        }

        $filePath = ACYM_TMP_FOLDER.plgAcymSendinblue::SENDING_METHOD_ID.'.txt';
        file_put_contents($filePath, "LASTNAME;FIRSTNAME;EMAIL\n");
        $limit = 5000;
        $buffer = '';

        foreach ($users as $oneUser) {
            $nameParts = explode(' ', $oneUser->name, 2);
            $lastName = str_replace('"', '', empty($nameParts[1]) ? '' : $nameParts[1]);
            $firstName = str_replace('"', '', $nameParts[0]);
            $buffer .= '"'.$lastName.'";"'.$firstName.'";"'.$oneUser->email."\"\n";
            $limit--;

            if ($limit === 0) {
                file_put_contents($filePath, $buffer, FILE_APPEND);
                $limit = 5000;
                $buffer = '';
            }
        }
        if (!empty($buffer)) {
            file_put_contents($filePath, $buffer, FILE_APPEND);
        }

        static $listId = null;

        if (empty($listId)) {
            $listId = $this->list->createList('Import '.time());
        }

        $data = [
            'fileUrl' => ACYM_TMP_URL.plgAcymSendinblue::SENDING_METHOD_ID.'.txt',
            'listIds' => [$listId],
            'updateExistingContacts' => true,
        ];

        $response->import = $this->callApiSendingMethod('contacts/import', $data, $this->headers, 'POST');

        $success = false;
        if (!empty($response->import['error_curl'])) {
            $message = acym_translationSprintf('ACYM_ERROR_OCCURRED_WHILE_CALLING_API', $response->import['error_curl']);
        } elseif (!empty($response->import['code'])) {
            $message = acym_translationSprintf('ACYM_API_RETURN_THIS_ERROR', $response->import['message']);
        } else {
            $message = acym_translation('ACYM_USERS_SUNCHRONIZED');
            $success = true;
        }
        if ($ajax) {
            acym_sendAjaxResponse($message, [], $success);
        } else {
            if (!$success) acym_enqueueMessage($message, 'error');
        }
    }

    public function deleteUsers($users)
    {
        $sendingMethod = $this->config->get('mailer_method', 'phpmail');
        if ($sendingMethod != plgAcymSendinblue::SENDING_METHOD_ID) return;

        $deleteUrl = 'contacts/';

        $userClass = new UserClass();

        foreach ($users as $oneUser) {
            $userToDelete = $userClass->getOneById($oneUser);
            $this->callApiSendingMethod($deleteUrl.urlencode($userToDelete->email), [], $this->headers, 'DELETE');
        }
    }

    public function createAttribute($mailId)
    {
        $existingAttributes = $this->config->get('sendinblue_attributes', '{}');
        $existingAttributes = json_decode($existingAttributes, true);

        $subjectAttributeName = $this->getSubjectAttributeName($mailId);
        $contentAttributeName = $this->getAttributeName($mailId);

        $added = $this->addAttribute($existingAttributes, $subjectAttributeName);
        $added = $this->addAttribute($existingAttributes, $contentAttributeName) || $added;

        if ($added) {
            $this->config->save(['sendinblue_attributes' => json_encode($existingAttributes)]);
        }
    }

    private function addAttribute(&$existingAttributes, $attributeName)
    {
        if (!empty($existingAttributes[$attributeName])) return false;
        $this->callApiSendingMethod(
            'contacts/attributes/normal/'.$attributeName,
            ['type' => 'text'],
            $this->headers,
            'POST'
        );
        $existingAttributes[$attributeName] = true;

        return true;
    }

    public function addUserToList($email, $mailId, &$warnings)
    {
        $listId = 0;
        $this->list->getListExternalSendingMethod($listId, $mailId);

        if (empty($listId)) return false;

        $data = [
            'emails' => [$email],
        ];

        $response = $this->callApiSendingMethod('contacts/lists/'.$listId.'/contacts/add', $data, $this->headers, 'POST');
        $success = !empty($response['contacts']) && !empty($response['contacts']['success']) && in_array($email, $response['contacts']['success']);
        $alreadyInList = !empty($response['message']) && strpos($response['message'], 'Contact already in list') !== false;

        if (!$success && !empty($response['message']) && !$alreadyInList) {
            $warnings .= $response['message'];
        }

        return $success || $alreadyInList;
    }

    public function removeUserFromList($mailId)
    {
        $listId = 0;
        $this->list->getListExternalSendingMethod($listId, $mailId);

        if (empty($listId)) return;

        $this->callApiSendingMethod('contacts/lists/'.$listId.'/contacts/remove', ['all' => true], $this->headers, 'POST');
    }

    public function addAttributeToUser($email, $subjectContent, $htmlContent, $mailId)
    {
        $subjectAttribute = $this->getSubjectAttributeName($mailId);
        $contentAttribute = $this->getAttributeName($mailId);

        if (strpos($htmlContent, 'acym__wysid__template') === false || strpos($htmlContent, '<body') === false) {
            $personalContent = $htmlContent;
        } else {
            $personalContent = preg_replace('#^.*<body[^>]*>(.*)</body>.*$#Uis', '$1', $htmlContent);
        }

        $data = [
            'attributes' => [
                $subjectAttribute => $subjectContent,
                $contentAttribute => $personalContent,
            ],
            'email' => $email,
            'updateEnabled' => true,
        ];
        $this->callApiSendingMethod('contacts', $data, $this->headers, 'POST');
    }

    public function getAttributeName($mailId)
    {
        return 'HTML_CONTENT_'.$mailId;
    }

    public function getSubjectAttributeName($mailId)
    {
        return 'SUBJECT_'.$mailId;
    }

    public function deleteAttribute($mailId)
    {
        $subjectAttributeName = $this->getSubjectAttributeName($mailId);
        $contentAttributeName = $this->getAttributeName($mailId);

        $existingAttributes = $this->config->get('sendinblue_attributes', '{}');
        $existingAttributes = json_decode($existingAttributes, true);

        $removedAnAttribute = $this->removeAttribute($existingAttributes, $subjectAttributeName);
        $removedAnAttribute = $this->removeAttribute($existingAttributes, $contentAttributeName) || $removedAnAttribute;

        if ($removedAnAttribute) {
            $this->config->save(['sendinblue_attributes' => json_encode($existingAttributes)]);
        }
    }

    private function removeAttribute(&$existingAttributes, $attributeName)
    {
        $this->callApiSendingMethod(plgAcymSendinblue::SENDING_METHOD_API_URL.'contacts/attributes/normal/'.$attributeName, [], $this->headers, 'DELETE');

        if (empty($existingAttributes[$attributeName])) return false;

        unset($existingAttributes[$attributeName]);

        return true;
    }

    public function synchronizeExistingUsers()
    {
        $userClass = new UserClass();
        $users = $userClass->getAllSimpleData();
        if (empty($users)) acym_sendAjaxResponse(acym_translation('ACYM_NO_USER_TO_SYNCHRONIZE'));

        $this->importUsers($users, true);
    }
}
