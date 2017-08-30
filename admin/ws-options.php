<?php defined('ABSPATH') or die("Restricted access!"); ?>

<div class="wrap">
    <h2>
        <div class="jumbotron">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <img src= '<?php echo WOO_SKROUTZ_URL . "/assets/images/woo-skroutz.png" ?>' alt="" class="img-responsive"/>
                    </div>
                </div>

                <div>
                    <h1 class="elegantshd"><?php echo WOO_SKROUTZ_NAME; ?></h1>
                </div>

                <h3>                    
                    <span>
                        <?php printf(__('by %s Jacob Malliaros %s', $this->text), '<a href="https://www.linkedin.com/in/iakovos-malliaros/" target="_blank">', '</a>'); ?>
                    </span>
                </h3>
                <p class="version"><?php _e('Version', $this->text); ?> <?php echo WOO_SKROUTZ_VERSION; ?></p>
            </div>
        </div>		
    </h2>
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>


    <div class="metabox-holder has-right-sidebar">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><?php _e("Settings", $this->text); ?></a></li>
            <li role="presentation"><a href="#usage" aria-controls="usage" role="tab" data-toggle="tab"><?php _e("Usage", $this->text); ?></a></li>
            <li role="presentation"><a href="#faq" aria-controls="faq" role="tab" data-toggle="tab"><?php _e("F.A.Q.", $this->text); ?></a></li>
            <li role="presentation"><a href="#author" aria-controls="author" role="tab" data-toggle="tab"><?php _e("Author", $this->text); ?></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="settings">    
                <div class="panel panel-info">
                    <div class="panel-heading"><?php _e("Settings", $this->text); ?></div>
                    <div class="panel-body">

                        <div class="inner-sidebar">
                            <div id="side-sortables" class="meta-box-sortabless ui-sortable">
                                <div id="about" class="postbox ">
                                    <h3 class="title"><?php _e('About', $this->text); ?></h3>
                                    <div class="inside">
                                        <?php _e('This plugin allows you to easily integrate your eshop into skroutz web site, providing you with the XML Feed.', $this->text); ?>
                                    </div>
                                </div>

                                <div id="support" class="postbox">
                                    <h3 class="title"><?php _e('Support', $this->text); ?></h3>
                                    <div class="inside">
                                        <p><?php _e('I\'m an independent developer, so every little contribution helps cover my costs and lets me spend more time building things for people like you to enjoy.', $this->text); ?></p>
                                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                            <input type="hidden" name="cmd" value="_s-xclick">
                                            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                        </form>                                
                                        <p><?php _e('Thanks for your support!', $this->text); ?></p>
                                    </div>
                                </div>

                                <div id="help" class="postbox">
                                    <h3 class="title"><?php _e('Help', $this->text); ?></h3>
                                    <div class="inside">
                                        <p><?php _e('If you have a question, please read the information in the FAQ section.', $this->text); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>            

                        <div class="row">
                            <div id="post-body-content" class="col-md-12">
                                <form action="options.php" method="post">
                                    <?php
                                    // output security fields for the registered setting "wskroutz"
                                    settings_fields($this->settings_page);
                                    // output setting sections and their fields
                                    // (sections are registered for "wskroutz", each field is registered to a specific section)
                                    do_settings_sections($this->settings_page);
                                    // output save settings button
                                    submit_button(__('Save Settings', $this->text));
                                    ?>
                                </form>    
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="usage">
                <div class="panel panel-info">
                    <div class="panel-heading"><?php _e("Usage", $this->text); ?></div>
                    <div class="panel-body">
                        <p><?php _e('To user the woo-skroutz plugin on your website, simply follow these steps:', $this->text); ?></p>
                        <ul class="list-group">
                            <li class="list-group-item">Go to the "Settings" tab.</li>
                            <li class="list-group-item">Select the desired settings.</li>
                            <li class="list-group-item">Click the "Save changes" button.</li>
                            <li class="list-group-item">You're done. The address of the XML feed that you should provide skroutz is: <a target="_blank" href="<?php echo $this->url ?>"><?php echo $this->url ?></a></li>
                        </ul>    
                        <p class="note"><b><?php _e('Note!', $this->text); ?></b> <?php _e('If you want more options then tell me and I will be happy to add it.', $this->text); ?></p>
                    </div>
                </div>        
            </div>
            <div role="tabpanel" class="tab-pane" id="faq">
                <div class="panel panel-info">
                    <div class="panel-heading"><?php _e("F.A.Q.", $this->text); ?></div>
                    <div class="panel-body">    
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <?php _e('Will this plugin work on my WordPress.COM website?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <?php _e('Sorry, this plugin is available for use only on self-hosted (WordPress.ORG) websites.', $this->text); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            <?php _e('Can I use this plugin on my language?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <?php _e('Yes, this plugin has been translated in two languages: Greek and English. If you are interested for another language, just let me know.', $this->text); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                            <?php _e('How does it work?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <?php _e('On the "Settings" tab, select the desired settings and click the "Save changes" button. Give <b>' . $this->url . '</b> address to the skroutz for the XML feed. It\'s that simple.', $this->text); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-danger">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                            <?php _e('Where to report bug if found?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        <?php printf(__('Please mail me at: %s', $this->text), '<a href="mailto:imalliar@gmail.com?subject=Bug Reporting about the ' . $this->name . ' plugin">imalliar@gmail.com</a>'); ?>
                                    </div>
                                </div>
                            </div>   
                            
                            <div class="panel panel-danger">
                                <div class="panel-heading" role="tab" id="headingFourOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFourOne" aria-expanded="true" aria-controls="collapseFourOne">
                                            <?php _e('I get the message "XML declaration allowed only at the start of the document" when I retrieve the XML feed.', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFourOne" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingFourOne">
                                    <div class="panel-body">
                                        <?php _e("Your theme or one of your plugins adds an extra line at the beggining of document. Unfortunatelly, the web stantard dicates that xml decleration shoud be at the first line of the document. You can:", $this->text); ?>
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                <?php _e("Disable all plugins and enable them one by one and see which creates the problem.", $this->text); ?>
                                            </li>
                                            <li class="list-group-item">
                                                <?php _e("Select a different theme.", $this->text); ?>
                                            </li>
                                            <li class="list-group-item">
                                                <?php _e("Download xml file and give it to skroutz manually.", $this->text); ?>
                                            </li>                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>                               

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingFive">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                            <?php _e('Where to share any ideas or suggestions to make the plugin better?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingFive">
                                    <div class="panel-body">
                                        <?php printf(__('Any suggestions are very welcome! Please send me an email to %s. Thank you!', $this->text), '<a href="mailto:imalliar@gmail.com?subject=Bug Reporting about the ' . $this->name . ' plugin">imalliar@gmail.com</a>'); ?>
                                    </div>
                                </div>
                            </div>                

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingSix">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                            <?php _e('I love this plugin! Can I help somehow?', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingSix">
                                    <div class="panel-body">
                                        <?php _e('Yes, any financial contributions are welcome! Just go to the "Support" tab and click on the donate button. Thank you!', $this->text); ?>
                                    </div>
                                </div>
                            </div>  

                            <div class="panel panel-primary">
                                <div class="panel-heading" role="tab" id="headingSeven">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                            <?php _e('My question wasn\'t answered here.', $this->text); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSeven" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingSeven">
                                    <div class="panel-body">
                                        <?php printf(__('You can ask your question by mail me at %s. But please keep in mind that this plugin is free, and there is no a special support team, so I have no way to answer everyone.', $this->text), '<a href="mailto:imalliar@gmail.com?subject=Bug Reporting about the ' . $this->name . ' plugin">imalliar@gmail.com</a>'); ?>
                                    </div>
                                </div>
                            </div>                  
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane" id="author">
                <div class="panel panel-info">
                    <div class="panel-heading"><?php _e("Author", $this->text); ?></div>
                    <div class="panel-body">            
                        <div class=row>
                            <div class="col-md-4">
                                <img src=<?php echo WOO_SKROUTZ_URL . 'assets/images/author.jpg'; ?> class="img-rounded img-responsive"/>
                            </div>
                            <div class="col-md-8">
                                <p> <?php _e("Hello. My name is Jacob Malliaros. I'm from Athens, Greece, EU."); ?></p>
                                <p> <?php _e("I'm an independent designer, software and web developer (full stack software engineer). I specialize on the Microsoft .NET, WordPress API, PHP, JQuery, HTML5. I’ve been working on projects for many people and organizations. Currently I work for the Greek Government."); ?> </p>
                                <p> <?php printf(__("You can contact me at: %s", $this->text), '<a href="https://www.linkedin.com/in/iakovos-malliaros/" target="_blank">LinkedIn</a>'); ?>
                                <p> <?php _e("If you appreciate my work, you can buy me a coffee!"); ?> </p>
                                <p> <?php _e("I spend a lot of time and effort trying to make this plugin.  But, I'm an independent developer, so every little contribution helps cover my costs and lets me spend more time building things for people like you to enjoy.") ?> </p>                
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                                    <input type="hidden" name="cmd" value="_s-xclick">
                                    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
                                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                </form>                        
                            </div>
                        </div>        
                    </div>
                </div>
            </div>

        </div>

    </div>



</div>


