// Client-side Javascript to select all the check-boxes on the screen
//   - Bill Marque (wlmarque@hewitt.com)
function SelectAll(){
	for( var i = 0; i < document.PacketForm.elements.length; i++ ){
		if( document.PacketForm.elements[i].type == 'checkbox' ){
			document.PacketForm.elements[i].checked = true;
		}
	}
}
function UnselectAll(){
	for( var i = 0; i < document.PacketForm.elements.length; i++ ){
		if( document.PacketForm.elements[i].type == 'checkbox' ){
			document.PacketForm.elements[i].checked = false;
		}
	}
}
