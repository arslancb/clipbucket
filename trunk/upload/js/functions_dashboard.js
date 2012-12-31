function update_dashboard_widget_states () {
    var parent = $('#dashboard-container'), parent_data = parent.data(), data = {};        
    data['mode'] = 'update_dasboard_widget_states';
    data['place'] = parent_data.place;    
    var closed_widgets = parent.find('.dashboard-widget.closed').map( function(){
        return $( this ).data('id')
    }).get();
    data['closed'] = closed_widgets.join();
    
    amplify.request('dashboards', data,function(d){
        if(d.err)
        {
            displayError( d.err );
        }
    });
}

function update_dashboard_widget_positions( event, ui ) {
    var parent = $('#dashboard-container'), parent_data = parent.data(), data = {};
    data['mode'] = 'update_dashboard_widget_positions';
    data['place'] = parent_data.place;
    data['importance'] = {};
    
    parent.find('.dashboard-widgets').each( function( index, dash ){
        var ddata = $( dash ).data();
        var widgets = $( dash ).find('.dashboard-widget').map( function(){
            return $(this).data('id')
        }).get();
        
        data['importance'][ ddata.importance ] = widgets.join();
    });
    
    amplify.request('dashboards', data,function(d){
        if(d.err)
        {
            displayError( d.err );
        }
    });
}

$( document ).ready( function( e ){
    $('.dashboard-widget-toggler').on( 'click', function(){
        var dashboard_widget_toggler = $( this ), dashboard_widget = dashboard_widget_toggler.parents('.dashboard-widget');
        dashboard_widget.toggleClass( 'closed' );

        update_dashboard_widget_states();
    } );
    
    $('#dashboard-container div[data-importance]').sortable({
        handle : '.dashboard-widget-handler',
        axis : 'y',
        placeholder: "ui-state-highlight",
        forcePlaceholderSize : true,
        update : function( event, ui ) {
            update_dashboard_widget_positions( event, ui );
        }
    });
});