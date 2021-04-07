$(document).ready(function () {
    $("form").submit(function (event) {

        $(".alert").remove();

        let cnp = {
            cnp: $("#cnp").val(),
        };

        $.ajax({
            type: "POST",
            url: "includes/cnp_validator.php",
            data: cnp,
            dataType: "json",
            encode: true,
        }).done(function (data) {
            console.log(data);

            if (!data.success) {
                if (data.errors.length) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.length + "</div>"
                    );
                }

                if (data.errors.format) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.format + "</div>"
                    );
                }

                if (data.errors.sex) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.sex + "</div>"
                    );
                }

                if (data.errors.date) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.date + "</div>"
                    );
                }

                if (data.errors.county) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.county + "</div>"
                    );
                }

                if (data.errors.controlKey) {
                    $("#result").append(
                        '<div class="alert alert-danger">' + data.errors.controlKey + "</div>"
                    );
                }

            } else {
                $("#result").append(
                    '<div class="alert alert-success">' + data.message + "</div>"
                );
            }
        });
        event.preventDefault();
    });
});