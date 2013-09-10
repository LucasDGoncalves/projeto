$(document).ready(function(){
	
  $(function() {
	    $( "#tabs" ).tabs();
	  });

	
	$('#display_register').click(function(){
		
		$.ajax({
			  url: "registerForm.php",
			}).done(function( msg ) {
				$( "#conteudo" ).html(msg);
				
				$("select[multiple]").asmSelect({
					addItemTarget: 'bottom',
					removeLabel: 'remover X'
				});
				
			});
	});
	
	$('#conteudo').delegate('#submit_register', 'click', function() {
		params = new Object();
		params.name = $('#register_name').val();
		params.login = $('#register_login').val();
		params.city = $('#register_city').val();
		params.friends = $('#register_friends').val();
		params.artists = $('#register_artists').val();
		
		$.ajax({
			  	type : 'POST',
				url: "submitRegisterForm.php",
				data : params,
			}).done(function( msg ) {
				$( "#conteudo" ).html(msg);
				
			});
	});
	
	$('#button_list').click(function(){
			
			$.ajax({
				  url: "listUsers.php",
				}).done(function( msg ) {
					$( "#conteudo" ).html(msg);
					
				    $("#usersList") 
				    .tablesorter({widthFixed: true, widgets: ['zebra']}) 
				    .tablesorterPager({container: $("#pager")}); 
					
				});
		});
	
	loadUser = function(login){
		$("#tab2_button").trigger('click');
		params = new Object();
		params.login = login;
		$.ajax({
			  type : 'POST',
			  url: "loadUser.php",
			  data : params
			}).done(function( msg ) {
				$( "#conteudo-2" ).html(msg);
				
			});
		
	};
	
	$('#conteudo-2').delegate('#button_cancel', 'click', function() {
		$('#button_cancel').hide();
		$('#button_save').hide();
		$('#button_edit').show();
		
		//habilitar campos de pessoa
		$('#edit_name').prop('disabled', true);
		$('#edit_city').prop('disabled', true);
		$('#edit_login').prop('disabled', true);
		
	});
	
	$('#conteudo-2').delegate('#button_edit', 'click', function() {
		//transformar botao editar em salvar e cancelar
		$('#button_edit').hide();
		$('#button_cancel').show();
		$('#button_save').show();
		
		//habilitar campos de pessoa
		$('#edit_name').prop('disabled', false);
		$('#edit_city').prop('disabled', false);
		$('#edit_login').prop('disabled', false);
		
		params = new Object();
		params.login = $('#edit_login').val();
		
		//habilitar campos de amigos e artistas
		$.ajax({
			  	type : 'POST',
				url: "getEditForms.php",
				data : params,
			}).done(function( msg ) {
				$( "#combos" ).html(msg);
				
				$("select[multiple]").asmSelect({
					addItemTarget: 'bottom',
					sortable: true,
					removeLabel: 'remover X'
				});
				
			});
	});
	
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
	 
  $(function() {
    $( "#combobox" ).combobox();
  });
});

