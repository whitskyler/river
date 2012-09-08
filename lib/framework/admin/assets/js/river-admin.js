/**
 * riverAdmin JavaScript|jQuery functions
 * 
 * Load into riverAdmin namespace
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  River Admin JS
 * @since       0.0.9
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */
(function($){
    
    riverAdmin = {
        
        name: 'riverAdmin',
        
        timePickers: riverAdminParams.timepicker || '',

	/**
	 * Tasks on page load
	 *
	 * @since 0.0.4
	 *
	 * @function
	 */
        pageLoad: function() {
            
            // Turn off autocomplete for all browsers
            $("form").attr("autocomplete", "off");
            $("html, body").animate({ scrollTop: 0 }, "slow");
        },
        
	/**
	 * nav Section Handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        navSectionHandler: function() {           

            // Populate the sections[]
            riverAdmin.nav.find('ul li').each(function(){
                // Get the menu title and slug
                var title = $(this).children('a').text();
                var slug = $(this).attr('id');

                // If not already defined, add the title into the array
                if( "undefined" == typeof riverAdmin.sections[title] ) {
                    //riverAdmin.sections.push(title);
                    riverAdmin.sections[title] = [];
                }

                // If not already defined, add the menu_slug into the array
                if( "undefined" == typeof riverAdmin.sections[title][slug] ) {
                    riverAdmin.sections[title].push(slug);
                } 

            });

            // Add div wrap around each section
            var section = riverAdmin.content.find('h3').wrap("<div class=\"section\">");

            section.each( function () {
               $(this).parent().append($(this).parent().nextUntil("div.section")); 
            });    

           // Add 'id' to the section div wrap 	
           $("div.section").each(function(index) {
                $(this).attr("id", riverAdmin.sections[$(this).children("h3").text()]);
                if (index == 0)
                    $(this).addClass("current");
                else
                    $(this).addClass("hide");
            });   

            // listen for the click event & then scroll to the section
            riverAdmin.navA.on( 'click', function() {
                
                // This this section to current
                riverAdmin.setCurrent( this, this.hash.substring(1) );

            });        

        },

        /**
         * Sets the active section hash, by adding 'curent' class
         * & removes 'current' from all the other nav links
         * 
         * It also sets the 'current' and 'hide' for the corresponding
         * content section.
         * 
         * @since 0.0.4
         * 
         * @function
         * @param obj       $this
         * @param string    hash url
         */
        setCurrent: function($this, hash) {

            if (hash) {

                riverAdmin.nav
                    .find('.current').removeClass('current').end()
                    .find('a[href=#' + hash + ']').parent().addClass('current');

                riverAdmin.content
                    .find('.current')
                        .removeClass('current')
                        .addClass('hide')
                        .end()
                    .find('div#' + hash )
                        .removeClass('hide')
                        .addClass('current')
                        .end();
                        
                
                // Allow a little time for the new section tab to load up
                setTimeout( function() {

                    // Scroll to the top of the screen if it's not there already
                    //if( $("html, body").offset().top != 0 ) {
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    //}

                    // Remove the hash from the URL
                    riverAdmin.removeURLHash();
                    
                }, 200);                        
                        
            } 
        },
      
	/**
	 * Helper function for confirming a user action.
	 *
	 * @since 0.0.0
	 *
	 * @function
	 *
	 * @param {String} text The text to display.
	 * @returns {Boolean}
	 */
	confirm: function (text) {
		return confirm(text) ? true : false;
	},
        
	/**
	 * datepicker handler
	 *
	 * @since 0.0.9
	 *
	 * @function
	 */        
        datepickerHandler: function() {
            
            riverAdmin.container.find( 'input[type=text].datepicker').datepicker();
            
        },

	/**
	 * imgselect handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        imgselectHandler: function() {

            /**
             * When the imgselect is clicked, remove 'selected' from all the
             * <label> elements and then add the 'selected' class to the
             * clicked img
             */
            $('label.imgselect input').on( 'click', function() {               
                // Walk up the DOM to the imgselect <div> for this imgselect group
                // Then remove 'selected' class from each of the <label> elements
                $(this).parent('label').parent('div').find('label').removeClass('selected');
                // Now assign 'selected' to the clicked image
                $(this).parent('label').addClass('selected');
            });         

        },
        
	/**
	 * timepicker handler
	 *
	 * @since 0.0.9
         * 
         * @uses riverAdmin.timePickers array passed in from wp_localize_script()
	 *
	 * @function
         * @link http://fgelinas.com/code/timepicker/
         * @link https://github.com/fgelinas/timepicker
	 */        
        timepickerHandler: function() {
            
            // Check to make sure the array got passed in from the caller
            if ( ! $.isArray( riverAdmin.timePickers ) )
                return;
            
            // For each timepicker, attach the .timepicker() script and define
            // its parameters (which are in the passed in array).
            riverAdmin.container.find( 'input[type=text].timepicker').each(function(i) {
                $(this).timepicker({
                    
                    /** Options ***************************************************/
                    // The character to use to separate hours and minutes. (default: ':')
                    timeSeparator: riverAdmin.timePickers[i]['args']['time_separator'],
                    // Define whether or not to show a leading zero for hours < 10. (default: true)
                    showLeadingZero: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_leading_zero'] ),
                    // Define whether or not to show a leading zero for minutes < 10. (default: true)
                    showMinutesLeadingZero: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_minutes_leading_zero'] ),
                    // Define whether or not to show AM/PM with selected time. (default: false)
                    showPeriod: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_period'] ),
                    // Define if the AM/PM labels on the left are displayed. (default: true)
                    showPeriodLabels: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_period_labels'] ),
                    // The character to use to separate the time from the time period.
                    periodSeparator: riverAdmin.timePickers[i]['args']['period_separator'],
                    // Define an alternate input to parse selected time to
                    altField: riverAdmin.timePickers[i]['args']['alt_field'],
                    // Used as default time when input field is empty or for inline timePicker
                    // (set to 'now' for the current time, '' for no highlighted time,
                    // default value: now)
                    defaultTime: riverAdmin.timePickers[i]['args']['default_time'],         

                    /** trigger options *******************************************/
                    // Define when the timepicker is shown.
                    // 'focus': when the input gets focus, 'button' when the button trigger element is clicked,
                    // 'both': when the input gets focus and when the button is clicked.
                    showOn: riverAdmin.timePickers[i]['args']['show_on'], 
                    // jQuery selector that acts as button trigger. ex: '#trigger_button'
                    button: 'null' == riverAdmin.timePickers[i]['args']['button'] ? null : riverAdmin.timePickers[i]['args']['button'],
                    
                    /** Localization **********************************************/
                    // Define the locale text for "Hours"
                    hourText: riverAdmin.timePickers[i]['args']['hour_text'], 
                    // Define the locale text for "Minute"
                    minuteText: riverAdmin.timePickers[i]['args']['minute_text'],
                    // Define the locale text for periods
                    amPmText: [riverAdmin.timePickers[i]['args']['am_pm_text']['am'], riverAdmin.timePickers[i]['args']['am_pm_text']['pm'] ],

                    /** Position **************************************************/
                    // Corner of the dialog to position, used with the jQuery UI Position utility if present.
                    myPosition: riverAdmin.timePickers[i]['args']['my_position'],
                    // Corner of the input to position
                    atPosition: riverAdmin.timePickers[i]['args']['at_position'],

                    /** custom hours and minutes **********************************/
                    hours: {
                        // First displayed hour
                        starts: riverAdmin.timePickers[i]['args']['hours']['starts'],
                        // Last displayed hour
                        ends: riverAdmin.timePickers[i]['args']['hours']['ends']
                    },
                    minutes: {
                        // First displayed minute
                        starts: riverAdmin.timePickers[i]['args']['minutes']['starts'],
                        // Last displayed minute
                        ends: riverAdmin.timePickers[i]['args']['minutes']['endss'],
                        // Interval of displayed minutes
                        interval: riverAdmin.timePickers[i]['args']['minutes']['interval']                       
                    },
                    // Number of rows for the input tables, minimum 2, makes more 
                    // sense if you use multiple of 2
                    rows: riverAdmin.timePickers[i]['args']['rows'],                   
                    // Define if the hours section is displayed or not. Set to 
                    // false to get a minute only dialog
                    showHours: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_hours'] ),                
                    // Define if the minutes section is displayed or not. Set to 
                    // false to get an hour only dialog
                    showMinutes: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_minutes'] ),

                    /** buttons ***************************************************/
                    // shows an OK button to confirm the edit
                    showCloseButton: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_close_button'] ),
                    // Text for the confirmation button (ok button)
                    closeButtonText: riverAdmin.timePickers[i]['args']['close_button_text'],
                    // Shows the 'now' button
                    showNowButton: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_now_button'] ),
                    // Text for the now button
                    nowButtonText: riverAdmin.timePickers[i]['args']['now_button_text'],
                    // Text for the now button
                    showDeselectButton: riverAdmin.convertToBoolean( riverAdmin.timePickers[i]['args']['show_deselect_button'] ),
                    // Text for the deselect button 
                    deselectButtonText: riverAdmin.timePickers[i]['args']['deselect_button_text']
                });
            });           
            
        },        
        
	/**
	 * upload-image handler
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */        
        uploadImageHandler: function() {
            
            $('.upload-button').on( 'click', function() {

                // grab the title tag, which we'll use in the header of the thickbox
                var title = $(this).attr( 'title' );
                // grab the targetfield to post the url back to
                targetfield = $(this).prev('.upload-url');

                // show Thickbox
                tb_show('Upload ' + title, 'media-upload.php?referer=wp-settings&type=image&TB_iframe=true&post_id=0', false);
                return false;
            });
            
            /**
             * Post the image's URL back to the targetfield, i.e. text field, and
             * then change the <img> src to the new URL
             */
            window.send_to_editor = function( html ) {
                
                imgurl = $('img', html).attr('src');
                $(targetfield)
                    .val(imgurl)
                    .parent('td')
                        .children('div#image-preview')
                            .css('display', 'block')
                            .children('img')
                                .attr('src', imgurl)
                                .end();

                tb_remove(); // close thickbox
            } 
            /**
             * Delete the image
             * 
             * Here we'll set the <img> src to '', hide the container for
             * image previewer, and clear out the upload-url text field val
             */
            $('div#image-preview a.delete-image').on('click', function(e) {
                e.preventDefault();
               
                $(this)
                    .prev('img')
                        .attr('src', '')
                        .end()
                    .parent('div')
                        .css('display', 'none')
                        .parent('td').children('input.upload-url')
                            .val('')
                            .end();

                    
            });
        },
        
        
	/**
	 * Convert the string parameter to Boolean
	 *
	 * @since 0.0.9
	 *
	 * @function
         * @param   string  String to convert
         * @return  bool    TRUE or FALSE
	 */ 
        convertToBoolean: function( stringToConvert ) {
               
            if( 'true' === stringToConvert || '1' === stringToConvert ) {
                return true;
            } else {
                return false;
            }


        },
        
	/**
	 * When a input or textarea field field is updated, update the character counter.
	 *
	 * For now, we can assume that the counter has the same ID as the field, with a _chars
	 * suffix. In the future, when the counter is added to the DOM with JS, we can add
	 * a data('counter', 'counter_id_here' ) property to the field element at the same time.
	 *
	 * @since 0.0.7
	 *
	 * @function
	 */
	updateCharacterCount: function() {

            //jQuery('#' + event.target.id + '_chars').html(jQuery(event.target).val().length.toString());           
            $( '#river_seo_title' ).keyup( function() {
                var $this = $(this);
                
               $( '#' + $this.attr('id') + '_chars' ).val( $this.val().length.toString() ); 

            });

            $( '#river_seo_description' ).keyup( function() {
                var $this = $(this);
                    
               $( '#' + $this.attr('id') + '_chars').val( $this.val().length.toString() ); 
            });                
	},          
        
	/**
	 * Removes the hash from the URL
	 *
	 * @since 0.0.4
	 *
	 * @function
	 */           
        removeURLHash: function () { 
            
            var loc = window.location.search.split('&');               

            //if url has '&reset=true or &error=true', remove it
            riverAdmin.pushState( loc[0] );
        },
        
	/**
	 * For HTML5 browsers, we use pushState; others (IE) use loc.hash.
	 *
	 * @since 0.0.6
	 *
	 * @function
	 */           
        pushState: function( finalLocSearch ) {          
            
            var loc = window.location;
            
            // If this browser supports HTML 5 history, use pushState
            if( Modernizr.history ) {
                
                history.pushState("", document.title, loc.pathname + finalLocSearch );
                
            } else {
                
                var scrollV, scrollH ;
                
                if ("pushState" in history) {
                    history.pushState("", document.title, loc.pathname + finalLocSearch );
                } else {                  
                    // Prevent scrolling by storing the page's current scroll offset
                    scrollV = document.body.scrollTop;
                    scrollH = document.body.scrollLeft;

                    loc.hash = "";

                    // Restore the scroll offset, should be flicker free
                    document.body.scrollTop = scrollV;
                    document.body.scrollLeft = scrollH;
                }                
            }            
            
        },      
        
	/**
	 * Time to initialize the river methods
	 *
	 * @since 0.0.0
	 *
	 * @function
	 */
        ready: function() {
            
            /**
             * Cache global variabes
             */
            riverAdmin.container = $('div#river-container');
            riverAdmin.mainSection = riverAdmin.container.find('section#main');
            riverAdmin.content = riverAdmin.mainSection.find('.content');
            riverAdmin.nav = riverAdmin.mainSection.find('nav#river-sections');
            riverAdmin.navA = riverAdmin.nav.find('a');
            riverAdmin.sections = [];            
            
            riverAdmin.pageLoad();
            riverAdmin.navSectionHandler();
            riverAdmin.datepickerHandler();
            riverAdmin.imgselectHandler();
            riverAdmin.timepickerHandler();
            riverAdmin.uploadImageHandler();
            
            riverAdmin.updateCharacterCount();
            
        }

    };
    
    /**
     * Launch river
     *
     * @since 0.0.0
     * 
     * @river
     */
    $(document).ready(function () {
        riverAdmin.ready();
    });
    
})(jQuery);

/**
 * Helper function for confirming a user action.
 *
 * This function is deprecated in favor of riverAdmin.confirm(text) which provides
 * the same functionality.
 *
 * @since 0.0.0
 */
function river_confirm(text) {
        return riverAdmin.confirm(text);
}
