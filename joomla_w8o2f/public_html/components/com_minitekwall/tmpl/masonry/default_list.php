<?php
/**
* @title		Minitek Wall
* @copyright   	Copyright (C) 2011-2021 Minitek, All rights reserved.
* @license   	GNU General Public License version 3 or later.
* @author url   https://www.minitek.gr/
* @developers   Minitek.gr
*/

defined('_JEXEC') or die;

if (!empty($this->wall) ||  $this->wall!== 0)
{
	$options = $this->getColumnsItemOptions();

	foreach ($this->wall as $key=>$item)
	{
		// Cat Filters
		$catfilter = '';
		
		if (isset($item->itemCategoriesRaw) && $item->itemCategoriesRaw)
		{
			foreach($item->itemCategoriesRaw as $category)
			{
				if (is_array($category))
				{
					$catfilter .= ' cat-'.$this->utilities->cleanName($category['category_name']);
				}
			}
		}
		else if (isset($item->itemCategoryRaw))
		{
			$catfilter .= ' cat-'.$this->utilities->cleanName($item->itemCategoryRaw);
		}

		// Tag Filters
		$tagfilter = '';

		if (isset($item->itemTags))
		{
			foreach($item->itemTags as $tag_name)
			{
				$tagfilter .= ' tag-'.$this->utilities->cleanName($tag_name->title);
			}
		}

		// Date Filters
		$datefilter = '';

		if (isset($item->itemDateRaw))
		{
			$datefilter .= ' date-'.\JHTML::_('date', $item->itemDateRaw, 'Y-m');
		}
		
		?><div class="mnwall-item <?php 
			echo $catfilter; ?> <?php 
			echo $tagfilter; ?> <?php 
			echo $datefilter; ?> <?php 
			echo $this->hoverEffectClass; ?>" style="padding:<?php 
			echo (int)$this->gutter; ?>px;" <?php 
			if (isset($item->itemID)) 
			{
				?>data-id="<?php echo (int)$item->itemID; ?>" <?php 
			}
			?>data-title="<?php echo strtolower(htmlspecialchars($item->itemTitleRaw)); ?>" <?php 
			if (isset($item->itemCategoryRaw)) 
			{
				?>data-category="<?php echo strtolower($this->utilities->cleanName($item->itemCategoryRaw)); ?>" <?php 
			}
			if (isset($item->itemAuthorRaw)) 
			{
				?>data-author="<?php echo strtolower($item->itemAuthorRaw); ?>" <?php 
			}
			if (isset($item->itemDateRaw)) 
			{
				?>data-date="<?php echo $item->itemDateRaw; ?>" <?php 
			}
			if (isset($item->itemHits)) 
			{
				?>data-hits="<?php echo (int)$item->itemHits; ?>" <?php 
			}
			if (isset($item->itemOrdering)) 
			{
				?>data-ordering="<?php echo (int)$item->itemOrdering; ?>" <?php 
			}
			if (isset($item->itemFOrdering)) 
			{
				?>data-fordering="<?php echo (int)$item->itemFOrdering; ?>" <?php 
			}
			if (isset($item->itemAlias)) 
			{
				?>data-alias="<?php echo $item->itemAlias; ?>" <?php 
			}
			if (isset($item->itemModified)) 
			{
				?>data-modified="<?php echo $item->itemModified; ?>" <?php 
			}
			if (isset($item->itemStart)) 
			{
				?>data-start="<?php echo $item->itemStart; ?>" <?php 
			}
			if (isset($item->itemFinish)) 
			{
				?>data-finish="<?php echo $item->itemFinish; ?>" <?php 
			}
			if (isset($item->rawIndex)) 
			{
				?>data-index="<?php echo $item->rawIndex; ?>"<?php 
			}
			?>><?php

			?><div class="mnwall-item-outer-cont <?php 
				if ($this->detailBoxColumns && (isset($item->itemImage) && $item->itemImage && $this->mas_images))
				{
					echo $options['position_class'];
				}
				?>" style="<?php 
					if ($this->mas_border_radius) 
					{
						?>border-radius: <?php echo $this->mas_border_radius; ?>px; <?php 
					}
					if ($this->mas_border) 
					{
						?>border: <?php echo $this->mas_border; ?>px solid <?php echo $this->mas_border_color; ?>; <?php 
					}
					echo $this->animated_flip; 
				?>"><?php 

				?><div class="mnwall-item-inner-cont" style="background-color: rgba(<?php echo $options['db_bg_class']; ?>,<?php echo $options['db_bg_opacity_class']; ?>);"><?php 
				
					if (isset($item->itemImage) && $item->itemImage && $this->mas_images) 
					{
						?><div class="mnwall-cover <?php echo $this->hoverEffectClass; ?>">
							<div class="mnwall-img-div" style=" <?php echo $this->animated_flip; ?>">
								<div class="mnwall-item-img"><?php 
									if (isset($item->itemLink) && $this->mas_image_link) 
									{
										?><a href="<?php echo $item->itemLink; ?>" class="mnwall-photo-link">
											<img src="<?php echo $item->itemImage; ?>" alt="<?php echo htmlspecialchars($item->itemTitleRaw); ?>" />
										</a><?php 
									} 
									else 
									{
										?><div
											class="mnwall-photo-link">
											<img src="<?php echo $item->itemImage; ?>" alt="<?php echo htmlspecialchars($item->itemTitleRaw); ?>" />
										</div><?php 
									}
								?></div><?php 
									
								if ($this->hoverBox) 
								{
									?><div class="mnwall-hover-box" style="<?php 
										echo $this->animated; 
										?>background-color: rgba(<?php echo $this->hb_bg_class; ?>,<?php echo $this->hb_bg_opacity_class; ?>);"><?php 

										?><div class="mnwall-hover-box-content <?php echo $this->hoverTextColor; ?>"><?php 
										
											if ($this->hoverBoxDate && isset($item->itemDate)) 
											{
												?><div class="mnwall-date"><?php 
													echo $item->itemDate; 
												?></div><?php 
											}

											if ($this->hoverBoxTitle) 
											{
												?><h3 class="mnwall-title"><?php 
													if (isset($item->itemLink) && $this->detailBoxTitleLink) 
													{
														?><a href="<?php echo $item->itemLink; ?>"><?php 
															echo $item->hover_itemTitle; 
														?></a><?php 
													} 
													else 
													{
														?><span><?php 
															echo $item->hover_itemTitle; 
														?></span><?php 
													}
												?></h3><?php 
											}

											if ($this->hoverBoxCategory || $this->hoverBoxAuthor) 
											{
												?><div class="mnwall-item-info"><?php 
												
													if (((isset($item->itemCategoryRaw) && $item->itemCategoryRaw) || (isset($item->itemCategoriesRaw) && $item->itemCategoriesRaw)) && $this->hoverBoxCategory) 
													{
														?><p class="mnwall-item-category">
															<span><?php echo \JText::_('COM_MINITEKWALL_IN'); ?> </span><?php 
															echo $item->itemCategory; 
														?></p><?php 
													}

													if (((isset($item->itemAuthorRaw) && $item->itemAuthorRaw) || (isset($item->itemAuthorsRaw) && $item->itemAuthorsRaw)) && $this->hoverBoxAuthor) 
													{
														?><p class="mnwall-item-author">
															<span><?php echo \JText::_('COM_MINITEKWALL_BY'); ?> </span><?php 
															echo $item->itemAuthor; 
														?></p><?php 
													}

												?></div><?php 
											}

											if ($this->hoverBoxIntrotext) 
											{
												if (isset($item->hover_itemIntrotext) && $item->hover_itemIntrotext) 
												{
													?><div class="mnwall-desc"><?php 
														echo $item->hover_itemIntrotext; ?>
													</div><?php 
												}
											}

											if ($this->hoverBoxHits && isset($item->itemHits)) 
											{
												?><div class="mnwall-hits">
													<p><?php echo $item->itemHits; ?>&nbsp;<?php echo \JText::_('COM_MINITEKWALL_HITS'); ?></p>
												</div><?php 
											}

											if ($this->hoverBoxLinkButton || $this->hoverBoxZoomButton) 
											{
												?><div class="mnwall-item-icons"><?php 
													if ($this->hoverBoxLinkButton) 
													{
														if (isset($item->itemLink)) 
														{
															?><a href="<?php echo $item->itemLink; ?>" class="mnwall-item-link-icon">
																<i class="fa fa-link"></i>
															</a><?php 
														}
													}

													
													if ($this->hoverBoxZoomButton && (isset($item->itemImage) && $item->itemImage && $this->mas_images)) 
													{
														?><a data-bs-toggle="modal" data-bs-target="#zoomWall_<?php echo $this->widgetID; ?>" class="mnwall-zoom mnwall-item-zoom-icon" data-src="<?php echo $item->itemImage; ?>" data-title="<?php echo htmlspecialchars($item->itemTitleRaw); ?>">
															<i class="fa fa-search"></i>
														</a><?php 												
													}
												?></div><?php 
											}

										?></div>
									</div><?php 
								}

							?></div>
						</div><?php 
					}

					if ($this->detailBoxAll) 
					{
						?><div class="mnwall-item-inner mnwall-detail-box <?php 
							echo $options['db_color_class']; ?> <?php 
							echo $options['db_class']; ?> <?php 
							echo $options['title_class']; ?> <?php 
							echo $options['introtext_class']; ?> <?php 
							echo $options['date_class']; ?> <?php 
							echo $options['category_class']; ?> <?php 
							echo $options['author_class']; ?> <?php 
							echo $options['tags_class']; ?> <?php 
							echo $options['hits_class']; ?> <?php 
							echo $options['count_class']; ?> <?php 
							echo $options['readmore_class']; ?> <?php 
							if (!isset($item->itemImage) || !$item->itemImage || !$this->mas_images)
							{
								echo 'mnw-no-image';
							} 
							?>"><?php

							if ($this->detailBoxDateAll && isset($item->itemDate)) 
							{
								?><div class="mnwall-date"><?php 
									echo $item->itemDate; 
								?></div><?php 
							}

							if ($this->detailBoxTitleAll) 
							{
								?><h3 class="mnwall-title"><?php 
									if (isset($item->itemLink) && $this->detailBoxTitleLink) 
									{
										?><a href="<?php echo $item->itemLink; ?>"><?php 
											echo $item->itemTitle; 
										?></a><?php 
									} 
									else 
									{
										?><span><?php 
											echo $item->itemTitle; 
										?></span><?php 
									}
								?></h3><?php 
							}

							
							if (($this->detailBoxCategoryAll && ((isset($item->itemCategoryRaw) && $item->itemCategoryRaw) || (isset($item->itemCategoriesRaw) && $item->itemCategoriesRaw))) || 
								($this->detailBoxAuthorAll && ((isset($item->itemAuthorRaw) && $item->itemAuthorRaw) || (isset($item->itemAuthorsRaw) && $item->itemAuthorsRaw))) ||
								($this->detailBoxTagsAll && isset($item->itemTags) && $item->itemTags && isset($item->itemTagsLayout))) 
							{
								?><div class="mnwall-item-info"><?php 
								
									if ($this->detailBoxCategoryAll && ((isset($item->itemCategoryRaw) && $item->itemCategoryRaw) || (isset($item->itemCategoriesRaw) && $item->itemCategoriesRaw))) 
									{
										?><p class="mnwall-item-category">
											<span><?php echo \JText::_('COM_MINITEKWALL_IN'); ?> </span><?php 
											echo $item->itemCategory; 
										?></p><?php 
									}

									if ($this->detailBoxAuthorAll && ((isset($item->itemAuthorRaw) && $item->itemAuthorRaw) || (isset($item->itemAuthorsRaw) && $item->itemAuthorsRaw))) 
									{
										?><p class="mnwall-item-author">
											<span><?php echo \JText::_('COM_MINITEKWALL_BY'); ?> </span><?php 
											echo $item->itemAuthor; 
										?></p><?php 
									}

									if ($this->detailBoxTagsAll && isset($item->itemTags) && $item->itemTags && isset($item->itemTagsLayout)) 
									{
										?><div class="mnwall-item-tags"><?php
											echo $item->itemTagsLayout;
										?></div><?php
									}

								?></div><?php 
							}

							if ($this->detailBoxIntrotextAll && isset($item->itemIntrotext) && $item->itemIntrotext) 
							{
								?><div class="mnwall-desc"><?php 
									echo $item->itemIntrotext;
								?></div><?php 
							}

							
							if ($this->detailBoxHitsAll && isset($item->itemHits)) 
							{
								?><div class="mnwall-hits">
									<p><?php echo $item->itemHits; ?>&nbsp;<?php echo \JText::_('COM_MINITEKWALL_HITS'); ?></p>
								</div><?php 
							}

							
							if ($this->detailBoxCountAll && isset($item->itemCount)) 
							{
								?><div class="mnwall-count">
									<p><?php echo $item->itemCount; ?></p>
								</div><?php 
							}

							
							if ($this->detailBoxReadmoreAll) 
							{
								if (isset($item->itemLink)) 
								{
									?><div class="mnwall-readmore">
										<a href="<?php echo $item->itemLink; ?>"><?php echo \JText::_('COM_MINITEKWALL_READ_MORE'); ?></a>
									</div><?php 
								}
							}

						?></div><?php 
					}

					if (! $this->mas_images || !isset($item->itemImage) || !$item->itemImage) 
					{
						if ($this->hoverBox) 
						{
							?><div class="mnwall-hover-box" style="<?php 
								echo $this->animated; 
								?> background-color: rgba(<?php echo $this->hb_bg_class; ?>,<?php echo $this->hb_bg_opacity_class; ?>);"><?php 

								?><div class="mnwall-hover-box-content <?php echo $this->hoverTextColor; ?>"><?php 
								
									if ($this->hoverBoxDate && isset($item->itemDate)) 
									{
										?><div class="mnwall-date"><?php 
											echo $item->itemDate; 
										?></div><?php 
									}

									if ($this->hoverBoxTitle) 
									{
										?><h3 class="mnwall-title"><?php 
											if (isset($item->itemLink) && $this->detailBoxTitleLink) 
											{
												?><a href="<?php echo $item->itemLink; ?>"><?php 
													echo $item->hover_itemTitle; 
												?></a><?php 
											} 
											else 
											{
												?><span><?php 
													echo $item->hover_itemTitle; 
												?></span><?php 
											}
										?></h3><?php 
									}

									if ($this->hoverBoxCategory || $this->hoverBoxAuthor) 
									{
										?><div class="mnwall-item-info"><?php 
										
											if (((isset($item->itemCategoryRaw) && $item->itemCategoryRaw) || (isset($item->itemCategoriesRaw) && $item->itemCategoriesRaw)) && $this->hoverBoxCategory) 
											{
												?><p class="mnwall-item-category">
													<span><?php echo \JText::_('COM_MINITEKWALL_IN'); ?> </span><?php 
													echo $item->itemCategory; 
												?></p><?php 
											}

											if (((isset($item->itemAuthorRaw) && $item->itemAuthorRaw) || (isset($item->itemAuthorsRaw) && $item->itemAuthorsRaw)) && $this->hoverBoxAuthor) 
											{
												?><p class="mnwall-item-author">
													<span><?php echo \JText::_('COM_MINITEKWALL_BY'); ?> </span><?php 
													echo $item->itemAuthor; 
												?></p><?php 
											}

										?></div><?php 
									}

									if ($this->hoverBoxIntrotext) 
									{
										if (isset($item->hover_itemIntrotext) && $item->hover_itemIntrotext) 
										{
											?><div class="mnwall-desc"><?php 
												echo $item->hover_itemIntrotext; 
											?></div><?php 
										}
									}

									if ($this->hoverBoxHits && isset($item->itemHits)) 
									{
										?><div class="mnwall-hits">
											<p><?php echo $item->itemHits; ?>&nbsp;<?php echo \JText::_('COM_MINITEKWALL_HITS'); ?></p>
										</div><?php 
									}

									if ($this->hoverBoxLinkButton || $this->hoverBoxZoomButton) 
									{
										?><div class="mnwall-item-icons"><?php 
											if ($this->hoverBoxLinkButton) 
											{
												if (isset($item->itemLink)) 
												{
													?><a href="<?php echo $item->itemLink; ?>" class="mnwall-item-link-icon">
														<i class="fa fa-link"></i>
													</a><?php 
												}
											}

											if ($this->hoverBoxZoomButton && (isset($item->itemImage) && $item->itemImage && $this->mas_images)) 
											{
												?><a data-bs-toggle="modal" data-bs-target="#zoomWall_<?php echo $this->widgetID; ?>" class="mnwall-zoom mnwall-item-zoom-icon" data-src="<?php echo $item->itemImage; ?>" data-title="<?php echo htmlspecialchars($item->itemTitleRaw); ?>">
													<i class="fa fa-search"></i>
												</a><?php 	
											}
										?></div><?php 
									}

								?></div>
							</div><?php 
						}

					}

				?></div>
			</div>
		</div><?php 
	}
} 
else 
{
	echo '-'; // =0 // for javascript purposes - Do not remove
}
