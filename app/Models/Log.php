<?php
/*
    * Log Model class to perform
    * log related database functions.
 */
use App\Libraries\Database;

class Log {
    /*
        * @var object $db - Database class object.
     */
    private $db;
    public function __construct() {
        $this->db = new Database;
    }
    /*
        * Inserts a log.
     */
    public function create($description) {
        $this->db->query('INSERT INTO system_logs (description) VALUES (:description)');

        $this->db->bind(':description', $description);

        return $this->db->execute();
    }
    /*
        * Returns list of logs.
     */
    public function get($paginate=false, $page=null, $perPage=null) {
        if(!$paginate)
            $this->db->query('SELECT * FROM system_logs ORDER BY created_at DESC');
        else {
            $this->db->query('SELECT COUNT(*) as total FROM jobs');
            $totalRows = $this->db->getResultSingle()->total;
            $offset = ($page-1) * $perPage;
            $totalPages = ceil($totalRows/$perPage);
            $this->db->query('SELECT * FROM system_logs ORDER BY created_at DESC LIMIT :offset, :per_page');
            $this->db->bind(':offset', $offset);
            $this->db->bind(':per_page', $perPage);
        }
        $result = $this->db->getResult();

        $toReturn = [
            'paginate' => $paginate,
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage,
            'data' => $result
        ];

        return $toReturn;
    }
}
