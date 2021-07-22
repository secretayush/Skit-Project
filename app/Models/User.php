<?php
/*
    * User Model class to perform
    * user related database functions.
 */
use App\Libraries\Database;

class User {
    /*
        * @var object $db - Database class object.
     */
    private $db;
    public function __construct() {
        $this->db = new Database;
    }
    /*
        * Inserts a new user to the database.
        * @var array $data - Data array.
        * @var string $role - Role of user.
        * @return boolean
     */
    public function register($data, $role) {
        if($role->title == 'company') {
            $this->db->query('INSERT INTO users (name, email, password, contact_number, company_identifier, role_id) VALUES (:name, :email, :password, :contact_number, :company_identifier, :role_id)');
            $this->db->bind(':company_identifier', $data['company_identifier']);
        }
        else if ($role->title == 'student') {

            $fileName = $_ENV['ROOT'] . '/storage/users/resumes/' . $data['resume']['name'];

            move_uploaded_file($data['resume']['tmp_name'], $fileName);

            $this->db->query('INSERT INTO users (name, email, password, contact_number, college_name, college_id, current_cgpa, active_backlogs, tenth_marks, twelveth_marks, resume, role_id, account_status) VALUES (:name, :email, :password, :contact_number, :college_name, :college_id, :current_cgpa, :active_backlogs, :tenth_marks, :twelveth_marks, :resume, :role_id, :account_status)');
            $this->db->bind(':college_name', $data['college_name']);
            $this->db->bind(':college_id', $data['college_id']);
            $this->db->bind(':current_cgpa', $data['current_cgpa']);
            $this->db->bind(':active_backlogs', $data['active_backlogs']);
            $this->db->bind(':tenth_marks', $data['tenth_marks']);
            $this->db->bind(':twelveth_marks', $data['twelveth_marks']);
            $this->db->bind(':resume', $fileName);
            $this->db->bind(':account_status', 1);
        }
        else if($role->title == 'hr') {
            $this->db->query('INSERT INTO users (name, email, password, contact_number, employee_id, role_id, company_id, account_status) VALUES (:name, :email, :password, :contact_number, :employee_id, :role_id, :company_id, :account_status)');
            $this->db->bind(':employee_id', $data['employee_id']);
            $this->db->bind(':company_id', $data['company_id']);
            $this->db->bind(':account_status', 1);
        }
        else if($role->title == 'moderator') {
            $this->db->query('INSERT INTO users (name, email, password, contact_number, role_id, account_status) VALUES (:name, :email, :password, :contact_number, :role_id, :account_status)');
            $this->db->bind(':account_status', 1);
        }

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':role_id', $role->id);

        return $this->db->execute();
    }
    /*
        * Returns user object based on email.
        * @param string $email - Email of user to search.
        * @return object
     */
    public function findUserByEmail($email) {
        $this->db->query('SELECT u.*, r.title, r.display_name FROM users u INNER JOIN roles r ON u.role_id = r.id WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->getResultSingle();
    }
    /*
        * Returns user object based on id.
        * @param string $id - ID of user to search.
        * @return object
     */
    public function find($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->getResultSingle();
    }
    /*
        * Returns user object if login
        * credentials are valid.
        * @param string $email - Email of user.
        * @param string $password - Password of user.
        * @return mixed | object or boolean.
     */
    public function login($email, $password) {
        $result = $this->findUserByEmail($email);
        if($result) {
            if(password_verify($password, $result->password))
                return $result;
        }
        return false;
    }

    public function findByJobID($job_id, $data){
        foreach($data as $jobs){
            if($jobs->job_id == $job_id){
                return $jobs;
            }
        }
        return false;
    }

    public function selectByRoleID($id) {
        $this->db->query('SELECT * FROM users WHERE role_id = :role_id');
        $this->db->bind(':role_id', $id);
        return $this->db->getResult();
    }
    /*
        * Returns array of companies, pagniated
        * or all based on parameters.
        * @param boolean $paginate - Whether to paginate the result or not.
        * @param int $page - Current page.
        * @param int $perPage - Results per page.
        * @return array
     */
    public function selectCompanies($paginate=false, $page=null, $perPage=null) {
        if(!$paginate)
            $this->db->query('SELECT * FROM users WHERE role_id = :role_id');
        else {
            $this->db->query('SELECT COUNT(*) as total FROM users WHERE role_id = :role_id');
            $this->db->bind(':role_id', 1);
            $totalRows = $this->db->getResultSingle()->total;
            $offset = ($page-1) * $perPage;
            $totalPages = ceil($totalRows/$perPage);
            $this->db->query('SELECT * FROM users WHERE role_id = :role_id LIMIT :offset, :per_page');
            $this->db->bind(':offset', $offset);
            $this->db->bind(':per_page', $perPage);
        }
        $this->db->bind(':role_id', 1);
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
    /*
        * Updates the account status of a user.
        * @param int $id - ID to find the user.
        * @param int $status - Status to change.
        * @param string $remarks - Remarks if any
        * @return boolean
     */
    public function updateAccountStatus($id, $status, $remarks=null) {
        $this->db->query('UPDATE users SET account_status = :status, remarks = :remarks WHERE id = :id');
        $this->db->bind(':id',$id);
        $this->db->bind(':status',$status);
        $this->db->bind(':remarks',$remarks);
        return $this->db->execute();
    }
    /*
        * Inserts the reset token into password_resets table.
        * @param string $email
        * @param string $token
        * @param datetime $expire
        * @return boolean
     */
    public function forgotPassword($email, $token, $expiry) {
        $this->db->query('INSERT INTO password_resets (email, token, expires_on) VALUES (:email, :token, :expires_on)');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        $this->db->bind(':expires_on', $expiry);
        return $this->db->execute();
    }
    /*
        * Updates the user's password.
        * @param string $email
        * @param string $password
        * @return boolean
     */
    public function resetPassword($email, $password) {
        $this->db->query('UPDATE users SET password = :password WHERE email = :email');
        $this->db->bind(':password', $password);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }
    /*
        * Finds an existing reset request based on e-mail
        * and token.
        * @param string $email
        * @param string $token
        * @return object
     */
    public function findResetRequest($email, $token) {
        $this->db->query('SELECT * FROM password_resets WHERE email = :email AND token = :token');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        return $this->db->getResultSingle();
    }
    /*
        * Deletes an existing password reset request.
        * @param string $email
        * @param string $token
        * @return boolean
     */
    public function deleteResetRequest($email, $token) {
        $this->db->query('DELETE FROM password_resets WHERE email = :email AND token = :token');
        $this->db->bind(':email', $email);
        $this->db->bind(':token', $token);
        return $this->db->execute();

    }
    /*
        * Returns array of students, pagniated
        * or all based on parameters.
        * @param boolean $paginate - Whether to paginate the result or not.
        * @param int $page - Current page.
        * @param int $perPage - Results per page.
        * @return array
     */
    public function selectStudents($id, $status, $remarks=null) {
        if(!$paginate)
            $this->db->query('SELECT * FROM users WHERE role_id = :role_id');
        else {
            $this->db->query('SELECT COUNT(*) as total FROM users WHERE role_id = :role_id');
            $this->db->bind(':role_id', 3);
            $totalRows = $this->db->getResultSingle()->total;
            $offset = ($page-1) * $perPage;
            $totalPages = ceil($totalRows/$perPage);
            $this->db->query('SELECT * FROM users WHERE role_id = :role_id LIMIT :offset, :per_page');
            $this->db->bind(':offset', $offset);
            $this->db->bind(':per_page', $perPage);
        }
        $this->db->bind(':role_id', 3);

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
?>
