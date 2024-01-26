// hláška při mazání js
const poleSmazat = document.querySelectorAll('.smazat')
for (const smazat of poleSmazat) {
    smazat.addEventListener('click', (udalost) => {
        if (confirm("Chcete stránku opravdu smazat !") == false) {
            udalost.preventDefault();
        }
    });
};
//poradi stranky jquery
$("#stranky").sortable({
    update: () => {
        const strankyId = $("#stranky").sortable("toArray");
        console.log(strankyId);

        $.ajax({
            url: "admin.php",
            data: {
                "poradi": strankyId,
            }

        })
    }
});