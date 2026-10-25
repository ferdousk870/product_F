<?php
require_once("db_config.php");

class Product
{
    // view products
    static function index()
    {
        global $db;
        $rs = $db->query("SELECT p.id, p.name, p.price, m.name AS manufacturer
                          FROM products p
                          JOIN manufacturers m ON m.id = p.manufacturer_id");

        while ($row = $rs->fetch_object()) {
            echo "<tr>
                    <td>{$row->id}</td>
                    <td>{$row->name}</td>
                    <td>{$row->price}</td>
                    <td>{$row->manufacturer}</td>
                    <td>
                       <a href='index.php?page=edit_product&id={$row->id}'>edit</a> |
                       <a href='index.php?page=delete_product&id={$row->id}'
                          onclick=\"return confirm('Delete?')\">delete</a>
                    </td>
                  </tr>";
        }
    }

    // manufacturer save (procedure call)
    static function saveMfg($name, $contact)
    {
        global $db;
        $db->query("CALL add_manufacturer('$name','$contact')");
    }

    //  product save (procedure call)
    static function saveProduct($name, $price, $mfg_id)
    {
        global $db;
        $db->query("CALL add_product('$name','$price','$mfg_id')");
    }

    // product update
    static function updateProduct($id, $name, $price, $mfg_id)
    {
        global $db;
        $db->query("UPDATE products
                    SET name='$name', price='$price', manufacturer_id='$mfg_id'
                    WHERE id='$id'");
    }

    // manufacturer delete trigger 
    static function deleteMfg($id)
    {
        global $db;
        $db->query("DELETE FROM manufacturers WHERE id='$id'");
    }

    // product delete
    static function deleteProduct($id)
    {
        global $db;
        $db->query("DELETE FROM products WHERE id='$id'");
    }

    // manufacturer dropdown
    static function dropdownMfg($selected = 0)
    {
        global $db;
        $rs = $db->query("SELECT id, name FROM manufacturers");

        while ($row = $rs->fetch_object()) {
            $sel = ($row->id == $selected) ? "selected" : "";
            echo "<option value='{$row->id}' $sel>{$row->name}</option>";
        }
    }


    static function getProduct($id)
    {
        global $db;
        $rs = $db->query("SELECT * FROM products WHERE id='$id'");
        return $rs->fetch_object();
    }
}
?>