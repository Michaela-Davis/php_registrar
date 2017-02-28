<?php
    class Student
    {
        private $student_name;
        private $date_of_enrollment;
        private $id;

        function __construct($student_name, $date_of_enrollment, $id = null)
        {
            $this->student_name = $student_name;
            $this->date_of_enrollment = $date_of_enrollment;
            $this->id = $id;
        }

        function getStudentName()
        {
            return $this->student_name;
        }

        function getDateOfEnrollment()
        {
            return $this->date_of_enrollment;
        }

        function getStudentId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (student_name, date_of_enrollment) VALUES ('{$this->getStudentName()}', '{$this->getDateOfEnrollment()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addCourse($course_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$this->getStudentId()}, {$course_id});");
        }

        function getSchedule()
        {
            $queries = $GLOBALS['DB']->query("SELECT courses.* FROM
                students JOIN students_courses ON (students_courses.student_id = students.id)
                         JOIN courses ON (courses.id = students_courses.course_id)
                WHERE students.id = {$this->getStudentId()};");
            $return_courses = [];
            foreach ($queries as $query)
            {
                $course_name = $query['course_name'];
                $course_number = $query['course_number'];
                $id = $query['id'];
                $new_course = new Course($course_name, $course_number, $id);
                array_push($return_courses, $new_course);
            }
            return $return_courses;
        }

        static function getAll()
        {
            $queries = $GLOBALS['DB']->query("SELECT * FROM students;");
            $return_student = $queries->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Student", array('student_name', 'date_of_enrollment', 'id'));
            // foreach ($queries as $query)
            // {
            //     $student_name = $query['student_name'];
            //     $date_of_enrollment = $query['date_of_enrollment'];
            //     $id = $query['id'];
            //     $student = new Student($student_name, $date_of_enrollment, $id);
            //     array_push($return_student,$student);
            // }
            return $return_student;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        static function findStudent($search_id)
        {
            $queries = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$search_id};");
            $return_student = null;
            foreach ($queries as $query)
            {
                $student_name = $query['student_name'];
                $date_of_enrollment = $query['date_of_enrollment'];
                $id = $query['id'];
                $return_student = new Student($student_name, $date_of_enrollment, $id);
            }
            return $return_student;
        }


    }

?>
