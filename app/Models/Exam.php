<?php

use App\Libraries\Database;

class Exam {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }
    /**
     * This function is used to store the exam data into database.
     * @param  [array] $data [details of the exam inserted by HR]
     * @return On successful insertion returns last insert Id else returns false.
     */
    public function create($data) {
        $this->db->query('INSERT INTO exams (name, description, duration, buffer_time, has_negative_marking, company_id, added_by) VALUES (:name, :description, :duration, :buffer_time, :has_negative_marking, :company_id, :added_by)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':buffer_time', $data['buffer_time']);
        $this->db->bind(':has_negative_marking', $data['has_negative_marking']);
        $this->db->bind(':company_id', $data['company_id']);
        $this->db->bind(':added_by', $data['added_by']);

        if($this->db->execute())
            return $this->db->getLastInsertId();

        return false;
    }

    public function getExams($cId, $paginate=false, $page=null, $perPage=null) {
        if(!$paginate)
            $this->db->query('SELECT e.*, u.employee_id, u.id as hr_id FROM exams e INNER JOIN users u ON e.added_by = u.id WHERE e.company_id = :company_id');
        else {
            $this->db->query('SELECT COUNT(*) as total FROM exams WHERE company_id = :company_id');
            $this->db->bind(':company_id', $cId);
            $totalRows = $this->db->getResultSingle()->total;
            $offset = ($page-1) * $perPage;
            $totalPages = ceil($totalRows/$perPage);
            $this->db->query('SELECT e.*, u.employee_id, u.id as hr_id FROM exams e INNER JOIN users u ON e.added_by = u.id WHERE e.company_id = :company_id LIMIT :offset, :per_page');
            $this->db->bind(':offset', $offset);
            $this->db->bind(':per_page', $perPage);
        }
        $this->db->bind(':company_id', $cId);

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
        $this->db->query('SELECT e.*, u.employee_id, u.id as hr_id FROM exams e INNER JOIN users u ON e.added_by = u.id WHERE e.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->getResultSingle();
    }

    public function changeStatus($id, $status, $remarks=null) {
        $this->db->query('UPDATE exams SET is_approved = :is_approved, remarks = :remarks WHERE id = :id');
        $this->db->bind(':id',$id);
        $this->db->bind(':is_approved', $status);
        $this->db->bind(':remarks', $remarks);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query('UPDATE exams SET description = :description, duration = :duration, buffer_time = :buffer_time, has_negative_marking = :has_negative_marking, is_approved = :is_approved WHERE id = :id');
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':buffer_time', $data['buffer_time']);
        $this->db->bind(':has_negative_marking', $data['has_negative_marking']);
        $this->db->bind(':is_approved', 0);
        $this->db->bind(':id', $id);

        $this->db->execute();

        $this->db->query('DELETE FROM questions WHERE exam_id = :exam_id');
        $this->db->bind(':exam_id', $id);

        return $this->db->execute();
    }

    public function getExamFromToken($token) {
        $this->db->query('SELECT eu.exam_id, eu.user_id, eu.exam_token, eu.exam_time, eu.started_at, eu.status, e.* FROM exam_user eu INNER JOIN exams e ON e.id = eu.exam_id WHERE eu.exam_token = :token');
        $this->db->bind(':token', $token);
        return $this->db->getResultSingle();
    }

    public function startExam($token, $startedAt) {
        $this->db->query('UPDATE exam_user SET started_at=:started_at, status=:status WHERE exam_token=:exam_token');
        $this->db->bind(':started_at', $startedAt);
        $this->db->bind(':status', 1);
        $this->db->bind(':exam_token', $token);
        return $this->db->execute();
    }

    public function registerAnswer($exam_id, $user_id, $answer) {
        $this->db->query('INSERT INTO user_answers (user_id, exam_id, answer) VALUES (:user_id, :exam_id, :answer)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':exam_id', $exam_id);
        $this->db->bind(':answer', $answer);
        return $this->db->execute();
    }

    public function stopExam($token) {
        $this->db->query('UPDATE exam_user SET status=:status WHERE exam_token=:exam_token');
        $this->db->bind(':status', 2);
        $this->db->bind(':exam_token', $token);
        return $this->db->execute();
    }
    public function showresults($cid){
        $this->db->query('SELECT eu.user_id, eu.exam_id, e.*, u.name as student_name FROM exam_user eu INNER JOIN exams e ON e.id = eu.exam_id INNER JOIN users u ON u.id = eu.user_id WHERE eu.exam_id IN (SELECT id FROM exams WHERE company_id = :company_id) AND eu.status = :status');
        $this->db->bind(':company_id', $cid);
        $this->db->bind(':status', 2);
        return $this->db->getResult();
    }
    public function getStudentAnswers($examid, $studentid) {
        $this->db->query('SELECT * FROM user_answers WHERE exam_id = :exam_id AND user_id = :user_id');
        $this->db->bind(':exam_id', $examid);
        $this->db->bind(':user_id', $studentid);
        return $this->db->getResult();
    }
}
