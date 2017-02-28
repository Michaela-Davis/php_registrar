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

        static function getAll()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $all_courses = [];
            foreach ($returned_courses as $course) {
                $course_name = $course['course_name'];
                $course_number = $course['course_number'];
                $id = $course['id'];
                $new_course = new Course($course_name, $course_number, $id);
                array_push($all_courses, $new_course);
            }
            return $all_courses;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
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
