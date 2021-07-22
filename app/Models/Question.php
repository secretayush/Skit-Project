<?php

use App\Libraries\Database;

class Question {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    public function create($data) {
        $this->db->query('INSERT INTO questions (description, option_one, option_two, option_three, option_four, correct_option, exam_id) VALUES (:description, :option_one, :option_two, :option_three, :option_four, :correct_option, :exam_id)');
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':option_one', $data['option_one']);
        $this->db->bind(':option_two', $data['option_two']);
        $this->db->bind(':option_three', $data['option_three']);
        $this->db->bind(':option_four', $data['option_four']);
        $this->db->bind(':correct_option', $data['correct_option']);
        $this->db->bind(':exam_id', $data['exam_id']);

        return $this->db->execute();
    }

    public function getExamQuestions($id) {
        $this->db->query("SELECT * FROM questions WHERE exam_id = :exam_id");
        $this->db->bind(":exam_id", $id);
        return $this->db->getResult();
    }
}
