// chkBoxes Javascript

<script>
  function chkBoxes() {
  	for (i=0; i < document.all.length; i++) {
		if (document.all(i).type == 'checkbox' && document.all(i).value == 'true') {
			document.all(i).checked = 'true';
		}
	}
  }
</SCRIPT>
