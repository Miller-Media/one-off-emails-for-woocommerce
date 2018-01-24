<?php

if (!defined('ABSPATH')) {
    die('Access denied.');
}

/**
 * Plugin Class
 */
class WooOneOffEmails
{

    /**
     * The settings class.
     *
     * @var WooOneOffEmailsSettings
     */
    public $settingsPage;


    /**
     * Admin stylesheet file.
     *
     */
    public $adminStyle;

    /**
     * Admin javascript file.
     *
     */
    public $adminScript;

    /**
     * WooOneOffEmails constructor.
     *
     * Initialize plugin properties/hooks.
     *
     */
    public function __construct ()
    {
        $this->settingsPage = new WooOneOffEmailsSettings();

        $this->adminStyle = plugins_url('woocommerce-one-off-emails/assets/css/admin.css', 'woocommerce-one-off-emails.php');
        $this->adminScript = plugins_url('woocommerce-one-off-emails/assets/js/admin.js', 'woocommerce-one-off-emails.php');

        // Hooks
        add_action('admin_enqueue_scripts', array ($this, 'adminEnqueueScripts'), 40, 1);

        // AJAX requests
        add_action('wp_ajax_wooe_sendemail', array ($this, 'ajaxSendEmail'));
        add_action('wp_ajax_wooe_previewemail', array($this, 'ajaxPreviewEmail'));
    }

    /**
     * Enqueue admin scripts and stylesheets.
     *
     * @param $hook string
     */
    public function adminEnqueueScripts ($hook)
    {
        // Only enqueue on appropriate admin screen.
        if ($hook !== 'woocommerce_page_wooe-menu')
            return;

        wp_enqueue_style('wooe_admin_style', $this->adminStyle);
        wp_enqueue_script('wooe_admin_script', $this->adminScript, array ('jquery'));
    }

    /**
     * Handles sending the email using values from the admin screen.
     *
     */
    public function ajaxSendEmail ()
    {
        $response = array ();
        if ((!isset($_POST['to']) || !$_POST['to']) ||
            (!isset($_POST['subject']) || !$_POST['subject']) ||
            (!isset($_POST['heading']) || !$_POST['heading']) ||
	        (!isset($_POST['message']) || !$_POST['message'])
        ) {
            $response['error'] = 'A required field is missing.';
            wp_die(json_encode($response));
        }

        $to = sanitize_text_field($_POST['to']);
        $reply_to_name = sanitize_text_field($_POST['reply_to_name']);
        $reply_to_email = sanitize_text_field($_POST['reply_to_email']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = html_entity_decode(stripslashes($_POST['message']));
        $heading = sanitize_text_field(stripslashes($_POST['heading']));

        // Check each recipient address to verify that it's a valid email address.
	    $addresses = explode(',', str_replace(' ', '', $to) );
	    $valid_addresses = array();
	    foreach( $addresses as $address){
	    	if( filter_var($address, FILTER_VALIDATE_EMAIL) )
	    		$valid_addresses[] = $address;
	    }
	    if( !count($valid_addresses) ){
	    	$response['error'] = 'No valid email addresses provided.';
	    	wp_die(json_encode($response));
	    }
	    $to = implode(',', $valid_addresses);

        $result = $this->sendMail($to, $reply_to_name, $reply_to_email, $subject, $heading, $message);

        $response['to'] = $to;
	    $response['reply_to_name'] = $reply_to_name;
	    $response['reply_to_email'] = $reply_to_email;
        $response['subject'] = $subject;
        $response['heading'] = $heading;
        $response['message'] = $message;

        $response['success'] = $result;
        wp_die(json_encode($response));
    }

	/**
	 * Handles previewing the email using values from the admin screen.
	 *
	 */
	public function ajaxPreviewEmail ()
	{
		$response = array ();
		if ((!isset($_POST['heading']) || !$_POST['heading']) ||
			(!isset($_POST['message']) || !$_POST['message'])
		) {
			$response['error'] = 'A required field is missing.';
			wp_die(json_encode($response));
		}

		$message = html_entity_decode(stripslashes($_POST['message']));
		$heading = sanitize_text_field(stripslashes($_POST['heading']));

		$preview = $this->previewMail($heading, $message);
		$response['message'] = $message;
		$response['result'] = $preview;

		wp_die(json_encode($response));
	}

	/**
	 * Send notification email using WC()->mailer.
	 *
	 * @param $to
	 * @param $reply_to_name
	 * @param $reply_to_email
	 * @param $subject
	 * @param $heading
	 * @param $message
	 * @return bool
	 * @internal param $reply_to
	 */
    public function sendMail( $to, $reply_to_name, $reply_to_email, $subject, $heading, $message )
    {
        if (is_woocommerce_activated()) {

        	// Filter "From Name"
	        if( $reply_to_name ){
		        add_filter( 'woocommerce_email_from_name', function($old_name) use ($reply_to_name){
			        return $reply_to_name;
		        } );
	        }

	        // Filter "From Email"
	        if( $reply_to_email ){
		        add_filter( 'woocommerce_email_from_address', function($from_address) use ($reply_to_email){
			        return $reply_to_email;
		        } );
	        }

            $mailer = WC()->mailer();
            $message = $mailer->wrap_message(
                $heading,
                $message
            );

            // Use $reply_to_email, if present.
            if( $reply_to_email ){
	            $headers = array( sprintf( 'Reply-To: %s', $reply_to_email ) );
	            return $mailer->send($to, $subject, $message, $headers);
            }

            return $mailer->send($to, $subject, $message);

        }

        return False;
    }

	/**
	 * Generate an email preview with WooCommerce styles.
	 *
	 * @param $heading
	 * @param $message
	 * @return string
	 */
    public function previewMail( $heading, $message )
    {
	    $mailer = WC()->mailer();
	    $email = new WC_Email();
	    $message = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $heading, $message ) ) );

	    return $message;
    }

}

if (!function_exists('is_woocommerce_activated')) {
    function is_woocommerce_activated ()
    {
        return class_exists('woocommerce');
    }
}