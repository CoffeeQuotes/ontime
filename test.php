<select id="test" name="test" value="">
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
    <option value="4">Four</option>
    <option value="5">Five</option>
</select>
<script>
    function removeOptionsByValue(selectElement, valuesToRemove) {
        var options = selectElement.options;
        for (var i = options.length - 1; i >= 0; i--) {
            if (valuesToRemove.includes(options[i].value)) {
                selectElement.remove(i);
            }
        }
    }

    var select = document.getElementById("test");
    var valuesToRemove = ["2", "4"]; // Specify the values to remove
    removeOptionsByValue(select, valuesToRemove);
</script>