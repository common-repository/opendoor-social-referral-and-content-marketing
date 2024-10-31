<?php

class OpenDoorAdmin
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $options2;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'OpenDoor Admin', 
            'OpenDoor', 
            'manage_options', 
            'opendoor-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'publisher_options' );
        
        /*
        print "<div><pre>";
        print_r($this->options);
        print "</pre></div>";
        */
        
        echo '<div class="wrap">';
        screen_icon();
        echo '  <h2>OpenDoor Admin</h2>';
        echo '  <form method="post" action="options.php">';

        settings_fields( 'publisher_options' );
        do_settings_sections( 'opendoor-admin' );
        
        $this->create_content_targeting();
        
        submit_button(); 
        
        echo '  </form>';
        echo '</div>';
        
        echo '<div class="wrap">';
        echo '  <h2>Advanced Settings</h2>';
        echo '  <p>Advanced settings and service options include:</p>';
        echo '  <ul style="list-style:initial; padding-left:30px;">';
        echo '      <li>UI/UX Personalization - Modify OpenDoor to look like a native part of your website</li>';
        echo '      <li>Enterprise Social Referrals - Limit social connections to members of a specific company or organization</li>';
        echo '      <li>Geo Targeting - Regionalize social connections by country, state, city and zip</li>';
        echo '      <li>Content Marketing - Automated distribution of media assets to relevant industry professionals</li>';
        echo '      <li>CRM Integration - Add new clients to your existing workflow</li>';
        echo '      <li>SEO - Best practices for boosting website rank using OpenDoor</li>';
        echo '      <li>Pro Assistance - Plugin Setup & Customization Service</li>';
        echo '  </ul>';
        echo '  <p>Contact <a href="mailto:julia.smart@opndr.com">julia.smart@opndr.com</a> with questions, requests and suggestions!</p>';
        echo '</div>';
        
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {   
        
        // ===== Application Settings
        register_setting(
            'publisher_options',                  // Option group
            'publisher_options',                  // Option name
            array( $this, 'sanitize' )              // Sanitize
        );

        // --- Section :: Application Settings 
        add_settings_section(
            'publisher_info_section',         // ID
            'Application Settings',                 // Title
            array( $this, 'publisher_info_section_info' ),   // Callback
            'opendoor-admin'                        // Page
        );

        // --- Field : site_name
	    add_settings_field(
            'site_name', 
            'Website or Publication Name', 
            array( $this, 'create_site_name_input' ), 
            'opendoor-admin', 
            'publisher_info_section'
        );   

        // --- Field : app_id
        add_settings_field(
            'app_id', 
            'Application ID (app_id)', 
            array( $this, 'create_app_id_input' ), 
            'opendoor-admin', 
            'publisher_info_section'
        );
        
        // --- Section :: Content Settings 
        add_settings_section(
            'content_targeting_section',                 // ID
            'Content Targeting',                        // Title
            array( $this, 'content_targeting_section_info' ),       // Callback
            'opendoor-admin'                            // Page
        );
              
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        // --- publisher_info
        if(!empty($input["publisher_info"])){
            
            // -- app_id
            if( isset( $input["publisher_info"]['app_id'] ) ) {
                if( preg_match( "/([0-9a-z]+\.[0-9a-z]+)/i", $input["publisher_info"]['app_id'], $matches ) ) {
                    $new_input["publisher_info"]['app_id'] = $matches[1];
                }
            }
                
            // -- site_name
            if( isset( $input["publisher_info"]['site_name'] ) ) {
                $new_input["publisher_info"]['site_name'] = sanitize_text_field( $input["publisher_info"]['site_name'] );
            }
        
        }
        
        
        // --- content_targeting
        if(!empty($input["content_targeting"])){
            
            if(
                !empty($input["content_targeting"])
                &&
                is_array($input["content_targeting"])
            ){
            
                // start a new sequential index regardless of the previous one...
                $newIndex = 0;
                foreach($input["content_targeting"] as $index => $contentSettingInfo){
                
                    $hasInfo = 0;
                
                    // -- uri
                    if( 
                        isset( $contentSettingInfo['uri'] ) 
                        &&
                        !empty($contentSettingInfo['uri'])
                    ) {
                        $new_input["content_targeting"][$newIndex]['uri'] = sanitize_text_field( $contentSettingInfo['uri'] );
                        $hasInfo = 1;
                    }
        
                    // -- indids
                    if( 
                        isset( $contentSettingInfo['indids'] ) 
                        &&
                        !empty($contentSettingInfo['indids'])
                    ) {
                        $new_input["content_targeting"][$newIndex]['indids'] = $contentSettingInfo['indids'];
                        $hasInfo = 1;
                    }
                    
                    if($hasInfo){
                        $newIndex++;
                    }
            
                }
            }
            
        }
        
        return $new_input;

    }
    
    
    /** 
     * Print the Section text
     */
    public function publisher_info_section_info()
    {
        print 'The following credentials are used to identify your website within OpenDoor.  This plugin will not activate without a valid app_id.';
    }
    
    public function content_targeting_section_info()
    {
        print 'Use the form below to set target industries for entire sections of your site.  These will automatically be applied as default to all content that matches the specified URI fragment but can be modified on a per article basis if needed.';
    }


    // --- create_site_name_input :: Get the settings option array and print one of its values
    public function create_site_name_input()
    {
        printf(
            '<input type="text" id="site_name" name="publisher_options[publisher_info][site_name]" value="%s" />',
            isset( $this->options["publisher_info"]['site_name'] ) ? esc_attr( $this->options["publisher_info"]['site_name']) : ''
        );
    }

    // --- create_app_id_input :: Get the settings option array and print one of its values
    public function create_app_id_input()
    {
        printf(
            '<input type="text" id="app_id" name="publisher_options[publisher_info][app_id]" value="%s" />',
            isset( $this->options["publisher_info"]['app_id'] ) ? esc_attr( $this->options["publisher_info"]['app_id']) : ''
        );
        echo '<p>Need an App Id? <a href="http://www.opndr.com/opn/apply" target="_blank">Apply here</a></p>';
    }


    
    
    
    
    // --- create_site_name_input :: Get the settings option array and print one of its values
    public function create_content_targeting_by_uri($inputs = array("id" => "", "selected" => array(), "uri" => ""))
    {
        

        echo '
        
                <div id="'.$inputs["id"].'"></div>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery("#'. preg_replace("/([\[\]])/", "\\\\\\\\\${1}", $inputs["id"]).'")
                            .targetContent({
                                "options" : opndr.industries,
                                "selected" : '. json_encode(array_flip($inputs["selected"])) .',
                                "input-name-root" : "'.$inputs["id"].'",
                                "uri" : "'.$inputs["uri"].'"
                            });
                    });
                </script>
                
        ';
        
        
    }
    
    
    // --- create_site_name_input :: Get the settings option array and print one of its values
    public function create_add_new_content_targeting_button($inputs = array("id" => "content-target-x"))
    {
        

        echo '
        
                <button class="button button-secondary" id="opendoor-add-content-target">+ Add URL</button>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery("#opendoor-add-content-target")
                            .data("id-root", "'.$inputs["id"].'")
                            .click(function(e) {
				                e.preventDefault();
				                var newContentTarget = jQuery("<div></div>")
				                    .appendTo(jQuery("div.content_targeting_section"));
				                newContentTarget
				                    .targetContent({
                                        "options" : opndr.industries,
                                        "selected" : {},
                                        "input-name-root" : (new Array("'.$inputs["id"].'[", new Date().getTime(), "]")).join(""),
                                        "uri" : ""
                                    });
                				return false;
                			});
                    });
                </script>
                
        ';
        
        
    }
    
    public function create_content_targeting()
    {
        
        
        echo '<div class="content_targeting_section">';
        
        // --- create on page stash of industries...
        $industriesJSON = file_get_contents('http://www.opndr.com/api/getindustrylist');
        
        if(!empty($industriesJSON)){
        
            $industryList = json_decode($industriesJSON);
            echo '<script type="text/javascript">';
            if(
                !empty($industryList->ok)
                &&
                !empty($industryList->ok->industries)
            ){
                echo '  opndr.industries = '. json_encode($industryList->ok->industries) . ';';
            } else {
                echo '  opndr.industries = {};';
            }
            echo '</script>';
            
        }
        
        if(
            !empty($this->options["content_targeting"])
        ){
            
            $currentOptions = $this->options["content_targeting"];
            
            $newIndex = 0;
            foreach($currentOptions as $index => $contentSettingInfo){
                
                $myIdRoot = "publisher_options[content_targeting][".$newIndex."]";
                $mySelected = $contentSettingInfo["indids"];
                $myUri = $contentSettingInfo["uri"];
                
                $this->create_content_targeting_by_uri(array(
                    "id" => $myIdRoot,
                    "uri" => $myUri,
                    "selected" => $mySelected
                ));
                
                $newIndex++;
                
            }
            
            
        } else {
            
            $newIndex = 0;
            
            $idRoot = "publisher_options[content_targeting][".$newIndex."]";
            
            $this->create_content_targeting_by_uri(array(
                "id" => $idRoot
            ));
            
        }
        
        echo '</div>';
        
        $this->create_add_new_content_targeting_button(array(
            "id" => "publisher_options[content_targeting]"
        ));

        
       
    }
    
    
}

if( is_admin() )
    $opendoor_admin = new OpenDoorAdmin();
?>