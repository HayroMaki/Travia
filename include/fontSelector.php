<div class="fontSelector">
    <select id="fontSelector" onchange="changeFont(this.value)">
        <option value="Syne">Human</option>
        <option value="Aurebesh">Aurebesh</option>
    </select>

    <script>
        function changeFont(font) {
            localStorage.setItem('font', font);
            document.body.style.fontFamily = localStorage.getItem('font');
        }
        function loadFont() {
            if (localStorage.getItem('font') != null) {
                document.body.style.fontFamily = localStorage.getItem('font');
            }
        }
    </script>
</div>