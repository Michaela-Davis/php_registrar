<?php
    class Course
    {
        private $course_name;
        private $course_number;
        private $id;

        function __construct($course_name, $course_number, $id = null)
        {
            $this->course_name = $course_name;
            $this->course_number = $course_number;
            $this->id = $id;
        }

        function getCourseName()
        {
            return $this->course_name;
        }

        function getCourseNumber()
        {
            return $this->course_number;
        }

        function getCourseId()
        {
            return $this->id;
        }

        function save()
        {
                $GLOBALS['DB']->exec("INSERT INTO courses (course_name, course_number) VALUES ('{$this->course_name}', '{$this->course_number}');");
                $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addStudent($student_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO students_courses (student_id, course_id) VALUES ({$student_id}, {$this->getCourseId()});");
        }

        function getClass()
        {
            $queries = $GLOBALS['DB']->query("SELECT students.* FROM
                courses JOIN students_courses ON (courses.id = students_courses.course_id)
                        JOIN students ON (students_courses.student_id = students.id)
                    WHERE courses.id = {$this->getCourseId()};");
            $return_students = [];
            // $returned_class = $queries->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Student", array('student_name', 'date_of_enrollment', 'id'));
            foreach ($queries as $query)
            {
                $student_name = $query['student_name'];
                $date_of_enrollment = $query['date_of_enrollment'];
                $id = $query['id'];
                $student = new Student($student_name, $date_of_enrollment, $id);
                array_push($return_students,$student);
            }
            return $return_students;

        }

        static function getAll()
        {
            $queries = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $all_courses = $queries->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Course", array('course_name','course_number','id'));
            // foreach ($returned_courses as $course) {
            //     $course_name = $course['course_name'];
            //     $course_number = $course['course_number'];
            //     $id = $course['id'];
            //     $new_course = new Course($course_name, $course_number, $id);
            //     array_push($all_courses, $new_course);
            // }
            return $all_courses;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
            $GLOBALS['DB']->exec("DELETE FROM students_courses;");
        }

        static function findCourse($search_id)
        {
            $queries = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$search_id};");
            $return_course = null;
            foreach ($queries as $query)
            {
                $course_name = $query['course_name'];
                $course_number = $query['course_number'];
                $id = $query['id'];
                $return_course = new Course($course_name, $course_number, $id);
            }
            return $return_course;
        }


    }
?>
