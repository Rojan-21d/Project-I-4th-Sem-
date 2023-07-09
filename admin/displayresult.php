<?php
function displayUser($result){
    echo "<table>";
    echo "<th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th><th>Action</th>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>"; // Added <tr> tag to start a new row
        echo "<td>" . $row['id'] . "</td>"; // Added <td> tags to wrap each data cell
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['contact'] . "</td>";
        echo "<td>" . $row['address'] . "</td>";
        echo "<td>Action</td>"; // Added a placeholder for the action column
        echo "</tr>"; // Added </tr> tag to close the row
    }
    echo "</table>";
}
?>
