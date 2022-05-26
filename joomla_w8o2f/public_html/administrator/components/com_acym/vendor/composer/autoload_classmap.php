<?php


$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'AcyMailing\\Classes\\ActionClass' => $baseDir . '/classes/action.php',
    'AcyMailing\\Classes\\AutomationClass' => $baseDir . '/classes/automation.php',
    'AcyMailing\\Classes\\CampaignClass' => $baseDir . '/classes/campaign.php',
    'AcyMailing\\Classes\\ConditionClass' => $baseDir . '/classes/condition.php',
    'AcyMailing\\Classes\\ConfigurationClass' => $baseDir . '/classes/configuration.php',
    'AcyMailing\\Classes\\FieldClass' => $baseDir . '/classes/field.php',
    'AcyMailing\\Classes\\FollowupClass' => $baseDir . '/classes/followup.php',
    'AcyMailing\\Classes\\FormClass' => $baseDir . '/classes/form.php',
    'AcyMailing\\Classes\\HistoryClass' => $baseDir . '/classes/history.php',
    'AcyMailing\\Classes\\ListClass' => $baseDir . '/classes/list.php',
    'AcyMailing\\Classes\\MailClass' => $baseDir . '/classes/mail.php',
    'AcyMailing\\Classes\\MailStatClass' => $baseDir . '/classes/mailstat.php',
    'AcyMailing\\Classes\\MailpoetClass' => $baseDir . '/classes/mailpoet.php',
    'AcyMailing\\Classes\\OverrideClass' => $baseDir . '/classes/override.php',
    'AcyMailing\\Classes\\PluginClass' => $baseDir . '/classes/plugin.php',
    'AcyMailing\\Classes\\QueueClass' => $baseDir . '/classes/queue.php',
    'AcyMailing\\Classes\\RuleClass' => $baseDir . '/classes/rule.php',
    'AcyMailing\\Classes\\SegmentClass' => $baseDir . '/classes/segment.php',
    'AcyMailing\\Classes\\StepClass' => $baseDir . '/classes/step.php',
    'AcyMailing\\Classes\\TagClass' => $baseDir . '/classes/tag.php',
    'AcyMailing\\Classes\\UrlClass' => $baseDir . '/classes/url.php',
    'AcyMailing\\Classes\\UrlClickClass' => $baseDir . '/classes/urlclick.php',
    'AcyMailing\\Classes\\UserClass' => $baseDir . '/classes/user.php',
    'AcyMailing\\Classes\\UserStatClass' => $baseDir . '/classes/userstat.php',
    'AcyMailing\\Classes\\ZoneClass' => $baseDir . '/classes/zone.php',
    'AcyMailing\\Controllers\\AutomationController' => $baseDir . '/controllers/automation.php',
    'AcyMailing\\Controllers\\BouncesController' => $baseDir . '/controllers/bounces.php',
    'AcyMailing\\Controllers\\CampaignsController' => $baseDir . '/controllers/campaigns.php',
    'AcyMailing\\Controllers\\ConfigurationController' => $baseDir . '/controllers/configuration.php',
    'AcyMailing\\Controllers\\DashboardController' => $baseDir . '/controllers/dashboard.php',
    'AcyMailing\\Controllers\\DeactivateController' => $baseDir . '/controllers/deactivate.php',
    'AcyMailing\\Controllers\\DynamicsController' => $baseDir . '/controllers/dynamics.php',
    'AcyMailing\\Controllers\\EntitySelectController' => $baseDir . '/controllers/entitySelect.php',
    'AcyMailing\\Controllers\\FieldsController' => $baseDir . '/controllers/fields.php',
    'AcyMailing\\Controllers\\FileController' => $baseDir . '/controllers/file.php',
    'AcyMailing\\Controllers\\FollowupsController' => $baseDir . '/controllers/followups.php',
    'AcyMailing\\Controllers\\FormsController' => $baseDir . '/controllers/forms.php',
    'AcyMailing\\Controllers\\GoproController' => $baseDir . '/controllers/gopro.php',
    'AcyMailing\\Controllers\\LanguageController' => $baseDir . '/controllers/language.php',
    'AcyMailing\\Controllers\\ListsController' => $baseDir . '/controllers/lists.php',
    'AcyMailing\\Controllers\\MailsController' => $baseDir . '/controllers/mails.php',
    'AcyMailing\\Controllers\\OverrideController' => $baseDir . '/controllers/override.php',
    'AcyMailing\\Controllers\\PluginsController' => $baseDir . '/controllers/plugins.php',
    'AcyMailing\\Controllers\\QueueController' => $baseDir . '/controllers/queue.php',
    'AcyMailing\\Controllers\\SegmentsController' => $baseDir . '/controllers/segments.php',
    'AcyMailing\\Controllers\\StatsController' => $baseDir . '/controllers/stats.php',
    'AcyMailing\\Controllers\\ToggleController' => $baseDir . '/controllers/toggle.php',
    'AcyMailing\\Controllers\\UpdateController' => $baseDir . '/controllers/update.php',
    'AcyMailing\\Controllers\\UsersController' => $baseDir . '/controllers/users.php',
    'AcyMailing\\Controllers\\ZonesController' => $baseDir . '/controllers/zones.php',
    'AcyMailing\\FrontControllers\\ArchiveController' => $baseDir . '/../../../components/com_acym/controllers/archive.php',
    'AcyMailing\\FrontControllers\\CronController' => $baseDir . '/../../../components/com_acym/controllers/cron.php',
    'AcyMailing\\FrontControllers\\FrontcampaignsController' => $baseDir . '/../../../components/com_acym/controllers/frontcampaigns.php',
    'AcyMailing\\FrontControllers\\FrontdynamicsController' => $baseDir . '/../../../components/com_acym/controllers/frontdynamics.php',
    'AcyMailing\\FrontControllers\\FrontentityselectController' => $baseDir . '/../../../components/com_acym/controllers/frontentityselect.php',
    'AcyMailing\\FrontControllers\\FrontfileController' => $baseDir . '/../../../components/com_acym/controllers/frontfile.php',
    'AcyMailing\\FrontControllers\\FrontlistsController' => $baseDir . '/../../../components/com_acym/controllers/frontlists.php',
    'AcyMailing\\FrontControllers\\FrontmailsController' => $baseDir . '/../../../components/com_acym/controllers/frontmails.php',
    'AcyMailing\\FrontControllers\\FrontservicesController' => $baseDir . '/../../../components/com_acym/controllers/frontservices.php',
    'AcyMailing\\FrontControllers\\FrontstatsController' => $baseDir . '/../../../components/com_acym/controllers/frontstats.php',
    'AcyMailing\\FrontControllers\\FronttoggleController' => $baseDir . '/../../../components/com_acym/controllers/fronttoggle.php',
    'AcyMailing\\FrontControllers\\FronturlController' => $baseDir . '/../../../components/com_acym/controllers/fronturl.php',
    'AcyMailing\\FrontControllers\\FrontusersController' => $baseDir . '/../../../components/com_acym/controllers/frontusers.php',
    'AcyMailing\\FrontControllers\\FrontzonesController' => $baseDir . '/../../../components/com_acym/controllers/frontzones.php',
    'AcyMailing\\FrontViews\\ArchiveViewArchive' => $baseDir . '/../../../components/com_acym/views/archive/view.html.php',
    'AcyMailing\\FrontViews\\FrontcampaignsViewFrontcampaigns' => $baseDir . '/../../../components/com_acym/views/frontcampaigns/view.html.php',
    'AcyMailing\\FrontViews\\FrontdynamicsViewFrontdynamics' => $baseDir . '/../../../components/com_acym/views/frontdynamics/view.html.php',
    'AcyMailing\\FrontViews\\FrontfileViewFrontfile' => $baseDir . '/../../../components/com_acym/views/frontfile/view.html.php',
    'AcyMailing\\FrontViews\\FrontlistsViewFrontlists' => $baseDir . '/../../../components/com_acym/views/frontlists/view.html.php',
    'AcyMailing\\FrontViews\\FrontmailsViewFrontmails' => $baseDir . '/../../../components/com_acym/views/frontmails/view.html.php',
    'AcyMailing\\FrontViews\\FrontusersViewFrontusers' => $baseDir . '/../../../components/com_acym/views/frontusers/view.html.php',
    'AcyMailing\\Helpers\\AutomationHelper' => $baseDir . '/helpers/automation.php',
    'AcyMailing\\Helpers\\BounceHelper' => $baseDir . '/helpers/bounce.php',
    'AcyMailing\\Helpers\\CaptchaHelper' => $baseDir . '/helpers/captcha.php',
    'AcyMailing\\Helpers\\CronHelper' => $baseDir . '/helpers/cron.php',
    'AcyMailing\\Helpers\\EditorHelper' => $baseDir . '/helpers/editor.php',
    'AcyMailing\\Helpers\\EncodingHelper' => $baseDir . '/helpers/encoding.php',
    'AcyMailing\\Helpers\\EntitySelectHelper' => $baseDir . '/helpers/entitySelect.php',
    'AcyMailing\\Helpers\\ExportHelper' => $baseDir . '/helpers/export.php',
    'AcyMailing\\Helpers\\FormPositionHelper' => $baseDir . '/helpers/formposition.php',
    'AcyMailing\\Helpers\\HeaderHelper' => $baseDir . '/helpers/header.php',
    'AcyMailing\\Helpers\\ImageHelper' => $baseDir . '/helpers/image.php',
    'AcyMailing\\Helpers\\ImportHelper' => $baseDir . '/helpers/import.php',
    'AcyMailing\\Helpers\\MailerHelper' => $baseDir . '/helpers/mailer.php',
    'AcyMailing\\Helpers\\MigrationHelper' => $baseDir . '/helpers/migration.php',
    'AcyMailing\\Helpers\\PaginationHelper' => $baseDir . '/helpers/pagination.php',
    'AcyMailing\\Helpers\\PluginHelper' => $baseDir . '/helpers/plugin.php',
    'AcyMailing\\Helpers\\QueueHelper' => $baseDir . '/helpers/queue.php',
    'AcyMailing\\Helpers\\RegacyHelper' => $baseDir . '/helpers/regacy.php',
    'AcyMailing\\Helpers\\SplashscreenHelper' => $baseDir . '/helpers/splashscreen.php',
    'AcyMailing\\Helpers\\TabHelper' => $baseDir . '/helpers/tab.php',
    'AcyMailing\\Helpers\\ToolbarHelper' => $baseDir . '/helpers/toolbar.php',
    'AcyMailing\\Helpers\\UpdateHelper' => $baseDir . '/helpers/update.php',
    'AcyMailing\\Helpers\\UserHelper' => $baseDir . '/helpers/user.php',
    'AcyMailing\\Helpers\\WorkflowHelper' => $baseDir . '/helpers/workflow.php',
    'AcyMailing\\Init\\ElementorForm' => $baseDir . '/wpinit/elementorForm.php',
    'AcyMailing\\Init\\acyActivation' => $baseDir . '/wpinit/activation.php',
    'AcyMailing\\Init\\acyAddons' => $baseDir . '/wpinit/addons.php',
    'AcyMailing\\Init\\acyBeaver' => $baseDir . '/wpinit/beaver.php',
    'AcyMailing\\Init\\acyCron' => $baseDir . '/wpinit/cron.php',
    'AcyMailing\\Init\\acyDeactivate' => $baseDir . '/wpinit/deactivate.php',
    'AcyMailing\\Init\\acyElementor' => $baseDir . '/wpinit/elementor.php',
    'AcyMailing\\Init\\acyFakePhpMailer' => $baseDir . '/wpinit/fake_phpmailer.php',
    'AcyMailing\\Init\\acyForms' => $baseDir . '/wpinit/forms.php',
    'AcyMailing\\Init\\acyGutenberg' => $baseDir . '/wpinit/gutenberg.php',
    'AcyMailing\\Init\\acyMenu' => $baseDir . '/wpinit/menu.php',
    'AcyMailing\\Init\\acyMessage' => $baseDir . '/wpinit/message.php',
    'AcyMailing\\Init\\acyOverrideEmail' => $baseDir . '/wpinit/override_email.php',
    'AcyMailing\\Init\\acyRouter' => $baseDir . '/wpinit/router.php',
    'AcyMailing\\Init\\acySecurity' => $baseDir . '/wpinit/security.php',
    'AcyMailing\\Init\\acyUpdate' => $baseDir . '/wpinit/update.php',
    'AcyMailing\\Init\\acyUsersynch' => $baseDir . '/wpinit/usersynch.php',
    'AcyMailing\\Init\\acyWpRocket' => $baseDir . '/wpinit/wprocket.php',
    'AcyMailing\\Libraries\\acymClass' => $baseDir . '/libraries/class.php',
    'AcyMailing\\Libraries\\acymController' => $baseDir . '/libraries/controller.php',
    'AcyMailing\\Libraries\\acymObject' => $baseDir . '/libraries/object.php',
    'AcyMailing\\Libraries\\acymParameter' => $baseDir . '/libraries/parameter.php',
    'AcyMailing\\Libraries\\acymPlugin' => $baseDir . '/libraries/plugin.php',
    'AcyMailing\\Libraries\\acymView' => $baseDir . '/libraries/view.php',
    'AcyMailing\\Libraries\\acympunycode' => $baseDir . '/libraries/punycode.php',
    'AcyMailing\\Types\\AclType' => $baseDir . '/types/acl.php',
    'AcyMailing\\Types\\CharsetType' => $baseDir . '/types/charset.php',
    'AcyMailing\\Types\\DelayType' => $baseDir . '/types/delay.php',
    'AcyMailing\\Types\\FailactionType' => $baseDir . '/types/failaction.php',
    'AcyMailing\\Types\\FileTreeType' => $baseDir . '/types/fileTree.php',
    'AcyMailing\\Types\\OperatorType' => $baseDir . '/types/operator.php',
    'AcyMailing\\Types\\OperatorinType' => $baseDir . '/types/operatorin.php',
    'AcyMailing\\Types\\UploadfileType' => $baseDir . '/types/uploadFile.php',
    'AcyMailing\\Views\\AutomationViewAutomation' => $baseDir . '/views/automation/view.html.php',
    'AcyMailing\\Views\\BouncesViewBounces' => $baseDir . '/views/bounces/view.html.php',
    'AcyMailing\\Views\\CampaignsViewCampaigns' => $baseDir . '/views/campaigns/view.html.php',
    'AcyMailing\\Views\\ConfigurationViewConfiguration' => $baseDir . '/views/configuration/view.html.php',
    'AcyMailing\\Views\\DashboardViewDashboard' => $baseDir . '/views/dashboard/view.html.php',
    'AcyMailing\\Views\\DynamicsViewDynamics' => $baseDir . '/views/dynamics/view.html.php',
    'AcyMailing\\Views\\FieldsViewFields' => $baseDir . '/views/fields/view.html.php',
    'AcyMailing\\Views\\FileViewFile' => $baseDir . '/views/file/view.html.php',
    'AcyMailing\\Views\\FormsViewForms' => $baseDir . '/views/forms/view.html.php',
    'AcyMailing\\Views\\GoproViewGopro' => $baseDir . '/views/gopro/view.html.php',
    'AcyMailing\\Views\\LanguageViewLanguage' => $baseDir . '/views/language/view.html.php',
    'AcyMailing\\Views\\ListsViewLists' => $baseDir . '/views/lists/view.html.php',
    'AcyMailing\\Views\\MailsViewMails' => $baseDir . '/views/mails/view.html.php',
    'AcyMailing\\Views\\OverrideViewOverride' => $baseDir . '/views/override/view.html.php',
    'AcyMailing\\Views\\PluginsViewPlugins' => $baseDir . '/views/plugins/view.html.php',
    'AcyMailing\\Views\\QueueViewQueue' => $baseDir . '/views/queue/view.html.php',
    'AcyMailing\\Views\\SegmentsViewSegments' => $baseDir . '/views/segments/view.html.php',
    'AcyMailing\\Views\\StatsViewStats' => $baseDir . '/views/stats/view.html.php',
    'AcyMailing\\Views\\UsersViewUsers' => $baseDir . '/views/users/view.html.php',
);
