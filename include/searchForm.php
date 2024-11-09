<script type="text/javascript">
    // autocomplete function from : https://www.w3schools.com/howto/howto_js_autocomplete.asp
    // Modified it a bit.
    function autocomplete(inp, arr) {
        // The autocomplete function takes two arguments :
        // The text field element and the array of possible autocompleted values
        var currentFocus;

        // Execute a function when someone writes in the text field
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;

            // Close any already open lists of autocompleted values
            closeAllLists();
            if (!val) { return false;}
            currentFocus = -1;

            // Create a DIV element that will contain the items (values)
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");

            // Append the DIV element as a child of the autocomplete container
            this.parentNode.appendChild(a);
            var count = 0;

            // For each item in the array :
            for (i = 0; i < arr.length; i++) {

                // Limit the number of items
                if (count > 8) break;

                // Check if the item starts with the same letters as the text field value
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {

                    // Create a DIV element for each matching element
                    b = document.createElement("DIV");

                    // Make the matching letters bold
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);

                    // Insert an input field that will hold the current array item's value
                    b.innerHTML += '<input type="hidden" value="' + arr[i] + '">';

                    // Execute a function when someone clicks on the item value (DIV element)
                    b.addEventListener("click", function(e) {

                        // Insert the value for the autocomplete text field
                        inp.value = this.getElementsByTagName("input")[0].value;

                        // Close the list of autocompleted values,
                        // (or any other open lists of autocompleted values)
                        closeAllLists();
                    });
                    a.appendChild(b);
                    count++;
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });
        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
        }
        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }
        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function (e) {
            closeAllLists(e.target);
        });
    }

    var planets = <?php echo json_encode(Planet::get_every_planet()); ?>
</script>

<form action="/travia/redirectTravel.php" method="get" autocomplete="off">
    <?php
    if (isset($error_msg)) {
        echo "<p style='color: darkred; text-align: center' ><b>$error_msg</b></p>";
    }
    ?>
    <div class="autocomplete">
        <div>
            <label for="formD"><b>Departure Planet</b></label>
            <br>
            <div class="search">
                <input type="text" name="Departure" id="formD" placeholder="Search planet...">
            </div>
        </div>
        <div>
            <label for="formA"><b>Destination Planet</b></label>
            <br>
            <div class="search">
                <input type="text" name="Destination" id="formA" placeholder="Search planet...">
            </div>
        </div>
        <div>
            <br>
            <input type="submit" value="OK">
        </div>
    </div>
</form>
<script type="text/javascript">
    autocomplete(document.getElementById('formD'), planets);
    autocomplete(document.getElementById('formA'), planets);
</script>
