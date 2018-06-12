<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Wording Helper
 *
 * @package	CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author	Sigit Prayitno
 * @link	https://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------
    if( !function_exists('wording_password_confirmation') ) {
    /**
    * Load Word when request forget password successful
    *
    * @return	word
    */
        function wording_password_confirmation($arrData = array()) {
            $baseurl = get_instance()->config->base_url();
            parse_str($arrData["data"]);
            if (!isset($email)) { $email = ""; }

            return '
                    <div class="form-group">
                        <h3>Check Your Email</h3>
                    <p>We have send and email to '.$email.', click the link in the email to reset your password. If you don\'t see the email, check other places it might be, like your junk, spam or other folders.</p>
                    </div>
                    ';
        }

    }
    
    if( !function_exists('wording_choose_service') ) {
    /**
    * Load Word when user need to choose service
    *
    * @return	word
    */
        function wording_choose_service() {
            $baseurl = get_instance()->config->base_url();
            return '
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Choose Service *</label>
                        <div class="col-lg-6">
                            <select data-placeholder="Choose Service" class="form-control" id="fservice" name="fservice" required="true">
                                <option value="R">Regular (day +1)</option>
                                <option value="U">Urgent (6 hours)</option>
                                <option value="VU">Very Urgent (3 hours)</option>
                                <option value="SD">Self Delivery</option>
                            </select>
                        </div>
                    </div>
                    ';
        }

    }
    
    if( !function_exists('wording_choose_service_small') ) {
    /**
    * Load Word when user need to choose service
    *
    * @return	word
    */
        function wording_choose_service_small() {
            $baseurl = get_instance()->config->base_url();
            return '
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Choose Service</label>
                        <div class="col-lg-3">
                            <select data-placeholder="Choose Service" class="form-control" id="fservice" name="fservice" required="true">
                                <option value="VU">Very Urgent (3 hours)</option>
                                <option value="U">Urgent (6 hours)</option>
                                <option value="R">Regular (day +1)</option>
                                <option value="SD">Self Delivery</option>
                            </select>
                        </div>
                    </div>
                    ';
        }

    }
    
    if( !function_exists('wording_choose_service_large') ) {
    /**
    * Load Word when user need to choose service
    *
    * @return	word
    */
        function wording_choose_service_large() {
            $baseurl = get_instance()->config->base_url();
            return '
                    <div class="form-group">
                        <label class="col-lg-6 control-label">Choose Service *</label>
                        <div class="col-lg-6">
                            <select data-placeholder="Choose Service" class="form-control" id="fservice" name="fservice" required="true">
                                <option value="R">Regular (day +1)</option>
                                <option value="U">Urgent (6 hours)</option>
                                <option value="VU">Very Urgent (3 hours)</option>
                                <option value="SD">Self Delivery</option>
                            </select>
                        </div>
                    </div>
                    ';
        }

    }