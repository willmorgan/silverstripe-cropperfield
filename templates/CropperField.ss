<div class="container js-cropperfield" data-field-id="$Name" style="max-width: 600px">
	<% with $Adapter %>
		<img src="$ImageFilename" class="js-cropperfield__target" />
	<% end_with %>
	<input type="hidden" class="js-cropperfield__data" name="{$Name}[Data]" />
</div>
