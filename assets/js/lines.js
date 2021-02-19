const lines = {
	getAll: function () {
		loading.show();
		$.ajax({
			url: "http://gistapis.etufor.ce.gov.br:8081/api/linhas/",
			method: "GET",
			success: function (data) {
				data.forEach((line) => {
					const newOption = new Option(
						line.numeroNome,
						line.numero,
						false,
						false
					);
					$("#lines").append(newOption).trigger("change");
				});
				loading.hide();
			},
			error: function (err) {
				console.log(err);
				loading.hide();
			},
		});
	},
	findHorary: function () {
		const lineNumber = $("#lines option:selected").val();
		if (!lineNumber) {
			alert("Busque por uma linha para pesquisar");
			return;
		}
		let date = $("#date").val();
		if (date == "") {
			alert("Informe uma data para pesquisar");
			return;
		}
		date = date.replace("-", "").replace("-", "").replace("-", "");
		const url = `http://gistapis.etufor.ce.gov.br:8081/api/horarios/${lineNumber}?data=${date}`;
		loading.show();
		$.ajax({
			url,
			method: "GET",
			success: function (data) {
				let html = "";
				data.forEach((route) => {
					html += `<div class="route">
								<div class="header-box-result">
									<h3>Ponto de partida: ${route.postoControle}</h3>
								</div>`;
					html += `<div class="hours-container">`;
					route.horarios.forEach((hour) => {
						html += `<div class="hour-item">${hour.horario}</div>`;
					});
					html += `</div>
					</div>`;
				});
				$(".box-result").html(html).fadeIn();
				loading.hide();
			},
			error: function (err) {
				console.log(err);
				loading.hide();
			},
		});
	},
};

const loading = {
	show: function () {
		$(".loading").addClass("show");
	},
	hide: function () {
		$(".loading").removeClass("show");
	},
};
