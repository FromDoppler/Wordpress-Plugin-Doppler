<div class="dp-library">
    <!-- This inline style is a hack to avoid loading content before the loading screen is hidden. -->
    <div class="dp-container" id="dplr_body_content" style="display: none;">
        <div class="dplr_settings">
            <a href="<?php _e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-form')?>" target="_blank" class="dplr-logo-header">
                <img id="" src="<?php echo DOPPLER_PLUGIN_URL?>admin/img/logo-doppler.svg" alt="Doppler logo"/>
            </a>

            <h2 class="main-title">
                <?php _e('Doppler Forms', 'doppler-form')?> <?php echo $this->get_version()?>
            </h2>
            <header class="hero-banner">
                <div class="dp-container">
                    <div class="dp-rowflex">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <h2><?php _e('Lists Managment', 'doppler-form')?></h2>
                        </div>
                        <div class="col-sm-7">
                            <p><?php _e('Here you will find all your Doppler\'s lists and be able to create new ones.', 'doppler-form')?></p>
                        </div>
                    </div>
                    <span class="arrow"></span>
                </div>
            </header>
            <div class="dp-rowflex d-none" id="ListErrorMessage">
                <div class="dp-wrap-message dp-wrap-cancel m-b-12">
                    <span class="dp-message-icon"></span>
                    <div class="dp-content-message">
                    </div>
                </div>
            </div>
            <div id="dplr-crud" class="dplr-tab-content dplr-tab-content--crud pb-1">
                <?php $this->display_success_message() ?>
                <?php $this->display_error_message() ?>
                <div id="showErrorResponse" class="messages-container blocker d-none"></div>
                <div id="showSuccessResponse" class="messages-container info d-none"></div>

                <form id="dplr-form-list-crud" class="mb-1" action="" method="post">
                    <div class="dp-rowflex" style="align-items:center;">
                        <div class="col-sm-10 col-md-10 col-lg-10 m-b-12">
                            <div class="awa-form">
                                <label for="listCreation" class="labelcontrol" aria-disabled="false">
                                    <?php _e('Create a Doppler List', 'doppler-form')?>
                                    <input type="text"
                                            id="listCreation"
                                            value=""
                                            maxlength="100"
                                            name="listCreation"
                                            class="box-shado-0"
                                            placeholder="<?php _e('Write the List name', 'doppler-form')?>">
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <button id="dplr-save-list" class="dp-button button-big primary-green button-medium" disabled="disabled">
                                <?php _e('Create List', 'doppler-form') ?>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="dplr-loading wrapper-loading pt-3">
                    <div class="loading-page">
                    </div>
                </div>
                
                <table class="dp-c-table" id="dplr-tbl-lists" aria-label="List grid" summary="List grid">
                <thead>
                    <tr>
                        <th aria-label="List name" scope="col">
                        <span><?php _e('Name', 'doppler-form')?></span>
                        </th>
                        <th aria-label="Subscribers amount" scope="col">
                        <span><?php _e('Subscribers', 'doppler-form')?></span>
                        </th>
                        <th aria-label="Actions" scope="col" style="width: 25px;">
                        <span><?php _e('Actions', 'doppler-form')?></span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
                
            <div id="dplr-dialog-confirm" title="<?php _e('Are you sure you want to delete the List?', 'doppler-form'); ?>">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span> <?php _e('If you proceed, the List will also be deleted in Doppler.', 'doppler-form')?></p>
            </div>
        </div>
    </div>
</div>