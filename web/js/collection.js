(function($) {
	var $body = $('body');

	function addFila($filas) {
		var prototype = $filas.data('prototype'),
			count = parseInt($filas.attr('data-count'), 10);

		if (!isNaN(count)) {
			$filas.append(prototype.replace(/__name__/g, count));
			count += 1;
			$filas.attr('data-count', count);	
		}	
	}

	function removeFila($fila) {
		var $filas = $fila.closest('.filas');
		$fila.remove();
		//Nota: en remove no debe actualizarse count, ya que count debe ser siempre
		//mayor al número correspodiente a la fila con número máximo.
	}

	$body
		.on('click', '.add-fila', function(e) {
			var $this = $(this);
				$filas = $($this.data('target'));

			e.preventDefault();
			if ($filas.length) {
				addFila($filas);
			}
		})
		.on('click', '.remove-fila', function(e) {
			var $this = $(this),
				$fila = $this.closest('.fila'),
				removeMessage = $.trim($(this).data('remove-message')),
				hasToRemove = true;

			e.preventDefault();
			if ($fila.length) {
				if (removeMessage !== '') {
					hasToRemove = confirm(removeMessage);
				}

				if (hasToRemove) {
					removeFila($fila);	
				}
			}
		});

}(jQuery));