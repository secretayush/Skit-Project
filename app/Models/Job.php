<?php

use App\Libraries\Database;

class Job {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    public function postjobs($data){
        $this->db->query('INSERT INTO jobs (title, no_of_openings, salary, expires_on, short_desc, company_id) VALUES (:title, :no_of_openings, :salary, :expires_on, :short_desc, :company_id)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':no_of_openings', $data['openings']);
        $this->db->bind(':salary', $data['salary']);
        $this->db->bind(':expires_on', $data['expiry_date']);
        $this->db->bind(':short_desc', $data['short_desc']);
        $this->db->bind(':company_id', $data['company_id']);
        return $this->db->execute();
    }

    public function showjobs(){
        $this->db->query('SELECT j.id, j.title, j.no_of_openings, j.salary, j.expires_on, j.short_desc, c.name, c.email, c.contact_number FROM jobs j inner join users c on j.company_id = c.id');
        return $this->db->getResult();
    }

    public function showappliedstudents($user_id){
        $this->db->query('SELECT * FROM (SELECT t.*, u.name as company_name, u.email as company_email, u.contact_number as company_no FROM (SELECT s.id as uid, s.*, j.title, j.no_of_openings, j.salary, j.expires_on, j.short_desc, j.company_id FROM job_user s inner join jobs j on s.job_id = j.id) t inner join users u on t.company_id = u.id) x inner join users z on x.user_id = z.id where user_id = :user_id;');
        $this->db->bind(':user_id', $user_id);
        return $this->db->getResult();
    }

    public function showshortlisted($company_name){
        $this->db->query('SELECT * FROM (SELECT t.*, u.name as company_name, u.email as company_email, u.contact_number as company_no FROM (SELECT s.id as uid, s.*, j.title, j.no_of_openings, j.salary, j.expires_on, j.short_desc, j.company_id FROM job_user s inner join jobs j on s.job_id = j.id) t inner join users u on t.company_id = u.id) x inner join users z on x.user_id = z.id where company_name = :company_name and status = :status');
        $this->db->bind(':company_name', $company_name);
        $this->db->bind(':status', 2);
        return $this->db->getResult();
    }

    public function showappliedjobs($company_name){
        $this->db->query('SELECT * FROM (SELECT t.*, u.name as company_name, u.email as company_email, u.contact_number as company_no FROM (SELECT s.id as uid, s.*, j.title, j.no_of_openings, j.salary, j.expires_on, j.short_desc, j.company_id FROM job_user s inner join jobs j on s.job_id = j.id) t inner join users u on t.company_id = u.id) x inner join users z on x.user_id = z.id where company_name = :company_name;');
        $this->db->bind(':company_name', $company_name);
        return $this->db->getResult();
    }

    public function check($job_id){
        $this->db->query('SELECT user_id FROM job_user where job_id = :job_id');
        $this->db->bind(':job_id', $job_id);
        return $this->db->getResult();
    }

    public function get($paginate=false, $page=null, $perPage=null) {
        if(!$paginate)
            $this->db->query('SELECT j.*, u.*, u.id as comp_id FROM jobs j INNER JOIN users u ON j.company_id = u.id');
        else {
            $this->db->query('SELECT COUNT(*) as total FROM jobs');
            $totalRows = $this->db->getResultSingle()->total;
            $offset = ($page-1) * $perPage;
            $totalPages = ceil($totalRows/$perPage);
            $this->db->query('SELECT u.*, u.id as comp_id, j.* FROM jobs j INNER JOIN users u ON j.company_id = u.id LIMIT :offset, :per_page');
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

    public function find($id) {
        $this->db->query('SELECT u.id as comp_id, j.* FROM jobs j INNER JOIN users u ON j.company_id = u.id WHERE j.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->getResultSingle();
    }

    public function changeStatus($id, $status, $remarks=null) {
        $this->db->query('UPDATE jobs SET is_approved = :is_approved, remarks = :remarks WHERE id = :id');
        $this->db->bind(':id',$id);
        $this->db->bind(':is_approved', $status);
        $this->db->bind(':remarks', $remarks);
        return $this->db->execute();
    }

    public function applyjobs($job_id, $user_id){
        $this->db->query('INSERT INTO job_user (job_id, user_id, created_at) VALUES (:job_id, :user_id, :created_at)');
        $this->db->bind(':job_id',$job_id);
        $this->db->bind(':user_id',$user_id);
        $this->db->bind(':created_at',date("Y-m-d H:i:s"));
        return $this->db->execute();
    }

    public function findstudent($user_id,$job_id) {
        $this->db->query('SELECT * FROM (SELECT t.*, u.name as company_name, u.email as company_email, u.contact_number as company_no FROM (SELECT s.*, j.title, j.no_of_openings, j.salary, j.expires_on, j.short_desc, j.company_id FROM job_user s inner join jobs j on s.job_id = j.id) t inner join users u on t.company_id = u.id) x inner join users z on x.user_id = z.id where user_id = :user_id and job_id = :job_id');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':job_id', $job_id);
        return $this->db->getResultSingle();
    }

    public function updateAccountStatus($user_id, $job_id, $status) {
        $this->db->query('UPDATE job_user SET status = :status WHERE user_id = :user_id and job_id = :job_id');
        $this->db->bind(':user_id',$user_id);
        $this->db->bind(':job_id',$job_id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function showexams($company_id){
        $this->db->query('SELECT * FROM exams WHERE company_id = :company_id and is_approved = 1');
        $this->db->bind(':company_id', $company_id);
        return $this->db->getResult();
    }

    public function allotExam($user_id, $exam_id, $exam_time, $token) {
        $this->db->query('INSERT INTO exam_user (exam_id, user_id, exam_time, exam_token) VALUES (:exam_id, :user_id, :exam_time, :exam_token)');
        $this->db->bind(':exam_id',$exam_id);
        $this->db->bind(':user_id',$user_id);
        $this->db->bind(':exam_time', $exam_time);
        $this->db->bind(':exam_token', $token);
        return $this->db->execute();
    }

    public function showallotedexams($id)
    {
        $this->db->query('SELECT * from exam_user s inner join (select e.id as e_id, e.name as exam_name,u.name as company_name  from exams e inner join users u on e.company_id = u.id) t on s.exam_id = t.e_id WHERE user_id = :user_id');
        $this->db->bind(':user_id', $id);
        return $this->db->getResult();
    }
}
?>
