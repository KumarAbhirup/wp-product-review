<?phpfunction cwp_pac_before_content($content) {    global $post;    $cwp_review_stored_meta = get_post_meta( $post->ID );    if(@$cwp_review_stored_meta['cwp_meta_box_check'][0]  == 'Yes' && (is_single() || is_page()) ) {    $return_string  = '<section id="review-statistics" class="article-section" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">                        <div class="review-wrap-up hreview clearfix">                            <div class="review-top clearfix">                                <h2 class="cwp-item" itemprop="itemreviewed">'.get_post_meta($post->ID, "cwp_rev_product_name", true).'</h2>                                <span class="cwp-item-price cwp-item"  itemprop="price">'.get_post_meta($post->ID, "cwp_rev_price", true).'</span>                            </div><!-- end .review-top -->                            <div class="review-wu-left">                                <div class="rev-wu-image">';    $product_image = get_post_meta($post->ID, "cwp_rev_product_image", true);    $imgurl = get_post_meta($post->ID, "cwp_image_link", true);    if(!empty($product_image)) {               if ($imgurl =="image")            $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );        if (!isset($feat_image) || $feat_image=="")            $feat_image = get_post_meta($post->ID, "cwp_product_affiliate_link", true);        $return_string .= '<a href="'.$feat_image.'" data-lightbox="'.$feat_image.'"><img  src="'.$product_image.'" alt="'. get_post_meta($post->ID, "cwp_rev_product_name", true).'" class="photo photo-wrapup"/></a>';    } else {        $return_string .= "<p class='no-featured-image'>".__("No image added.", "cwppos")."</p>";    }    $rating = cwppos_calc_overall_rating($post->ID);       for($i=1; $i<=cwppos("cwppos_option_nr"); $i++) {        ${"option".$i."_content"} = get_post_meta($post->ID, "option_".$i."_content", true);        if(empty(${"option".$i."_content"})) {            ${"option".$i."_content"} = __("Default Feature ".$i, "cwppos");        }    }    ?>    <?php    $commentNr = get_comments_number($post->ID) + 2;    $divrating = $rating['overall']/10;    $return_string .= '</div><!-- end .rev-wu-image -->                            <div class="review-wu-grade">                                <div class="cwp-review-chart">                                <meta itemprop="dtreviewed" datetime="'.get_the_time("Y-m-d", $post->ID).'">                                <meta itemprop="reviewer" content="'.get_the_author().'">                                <meta itemprop="count" content="'.$commentNr.'">                                    <div temprop="reviewRating" itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating" class="cwp-review-percentage" data-percent="';    $return_string .= $rating['overall'].'"><span itemprop="value" class="cwp-review-rating">'.$divrating.'</span><meta itemprop="best" content = "10"/></div>                                </div><!-- end .chart -->                            </div><!-- end .review-wu-grade -->                            <div class="review-wu-bars">';    for($i=1; $i<=cwppos("cwppos_option_nr"); $i++) {    if (!empty(${'option'.$i.'_content'}) && isset($rating['option'.$i]) && (!empty($rating['option'.$i]) || $rating['option'.$i] === '0' ) &&  strtoupper(${'option'.$i.'_content'}) != 'DEFAULT FEATURE '.$i) {        $return_string .= '<div class="rev-option" data-value='.$rating['option'.$i].'>                                        <div class="clearfix">                                            <h3>'. ${'option'.$i.'_content'}.'</h3>                                            <span>'.$rating['option'.$i].'/10</span>                                        </div>                                        <ul class="clearfix"></ul>                                    </div>';    }    }    $return_string .='                            </div><!-- end .review-wu-bars -->                        </div><!-- end .review-wu-left -->                        <div class="review-wu-right">                            <div class="pros">';    for($i=1; $i<=cwppos("cwppos_option_nr"); $i++) {        ${"pro_option_".$i} = get_post_meta($post->ID, "cwp_option_".$i."_pro", true);        if(empty(${"pro_option_".$i})) {            ${"pro_option_".$i}  = "" ;        }    }    for($i=1; $i<=cwppos("cwppos_option_nr"); $i++) {        ${"cons_option_".$i} = get_post_meta($post->ID, "cwp_option_".$i."_cons", true);        if(empty(${"cons_option_".$i})) {            ${"cons_option_".$i}  = "";        }    }    $return_string .=  '<h2>'.__(cwppos("cwppos_pros_text"), "cwppos").'</h2>                                <ul>';    for($i=1;$i<=cwppos("cwppos_option_nr");$i++) {        if(!empty(${"pro_option_".$i})) {            $return_string .=  '   <li>- '.${"pro_option_".$i}.'</li>';        }    }    $return_string .= '     </ul>                            </div><!-- end .pros -->                            <div class="cons">';    $return_string .=' <h2>'.__(cwppos("cwppos_cons_text"), "cwppos").'</h2>  <ul>';    for($i=1;$i<=cwppos("cwppos_option_nr");$i++){        if(!empty(${"cons_option_".$i})) {            $return_string .=  '   <li>- '.${"cons_option_".$i}.'</li>';        }    }    $return_string .='                                </ul>                            </div>';    $return_string .='                        </div><!-- end .review-wu-right -->                        </div><!-- end .review-wrap-up -->                    </section><!-- end #review-statistics -->';    if(cwppos("cwppos_show_poweredby") == 'yes' && !class_exists('CWP_PR_PRO_Core')) {        $return_string.='<div style="font-size:12px;width:100%;float:right"><p style="float:right;">Powered by <a href="http://wordpress.org/plugins/wp-product-review/" target="_blank" rel="nofollow" > WP Product Review</a></p></div>';    }    $affiliate_text = get_post_meta($post->ID, "cwp_product_affiliate_text", true);    $affiliate_link = get_post_meta($post->ID, "cwp_product_affiliate_link", true);    $affiliate_text2 = get_post_meta($post->ID, "cwp_product_affiliate_text2", true);    $affiliate_link2 = get_post_meta($post->ID, "cwp_product_affiliate_link2", true);    if(!empty($affiliate_text2) && !empty($affiliate_link2)) {        $bclass="affiliate-button2 affiliate-button";    }    else        $bclass="affiliate-button";    if(!empty($affiliate_text) && !empty($affiliate_link)) {        $return_string .= '<div class="'.$bclass.'">                                    <a href="'.$affiliate_link.'" rel="nofollow" target="_blank"><span>'. $affiliate_text.'</span> </a>                                 </div><!-- end .affiliate-button -->';    }        if(!empty($affiliate_text2) && !empty($affiliate_link2)) {        $return_string .= '<div class="affiliate-button affiliate-button2">                                    <a href="'.$affiliate_link2.'" rel="nofollow" target="_blank"><span>'. $affiliate_text2.'</span> </a>                                 </div><!-- end .affiliate-button -->';    }        if(@$cwp_review_stored_meta['cwp_meta_box_check'][0]  == 'Yes' && (is_single() || is_page()) ) {        if(cwppos("cwppos_show_reviewbox") == 'yes') return $content.$return_string;        if(cwppos("cwppos_show_reviewbox") == 'no') return $return_string.$content;        return $content.$return_string;    } else return $content;}elsereturn $content;}$currentTheme = wp_get_theme();if ($currentTheme->get('Name') !== 'Bookrev') {    add_filter('the_content', 'cwp_pac_before_content');}?>