<?php
/*
    * Role Model class to perform
    * role related database functions.
 */
use App\Libraries\Database;

class Role {
    /*
        * @var object $db - Database class object.
     */
    private $db;
    public function __construct() {
        $this->db = new Database;
    }
    /*
        * Returns role object based on name.
        * @param string $role - Name of role to search.
     */
    public function findRoleByName($role) {
        $this->db->query('SELECT * FROM roles WHERE title = :title');

        $this->db->bind(':title', $role);

        $row = $this->db->getResultSingle();

        return $row;
    }
}
