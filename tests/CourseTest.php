<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Course.php";
    require_once "src/Student.php";

    $server = 'mysql:host=localhost:8889;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class CourseTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Course::deleteAll();
            Student::deleteAll();
        }

        function test_save()
        {
            // Arrange
            $course_name = "Macro Economics";
            $course_number = "ECON101";
            $test_course = new Course($course_name, $course_number);
            $test_course->save();

            // Act
            $result = Course::getAll();

            // Assert
            $this->assertEquals($test_course, $result[0]);
        }

        function test_getAll()
        {
            // Arrange
            $course_name = "Macro Economics";
            $course_number = "ECON101";
            $test_course = new Course($course_name, $course_number);
            $test_course->save();

            $course_name2 = "Intro to Psychology";
            $course_number2 = "PSYC101";
            $test_course2 = new Course($course_name, $course_number);
            $test_course2->save();

            // Act
            $result = Course::getAll();

            // Assert
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function test_find()
        {
            // Arrange
            $course_name = "Macro Economics";
            $course_number = "ECON101";
            $test_course = new Course($course_name, $course_number);
            $test_course->save();

            $course_name2 = "Intro to Psychology";
            $course_number2 = "PSYC101";
            $test_course2 = new Course($course_name, $course_number);
            $test_course2->save();

            // Act
            $result = Course::findCourse($test_course2->getCourseId());

            // Assert
            $this->assertEquals($test_course2,$result);
        }

        function test_getClass()
        {
            // Arrange
            $student_name = "Ada Lovelace";
            $date_of_enrollment = "2017-02-28";
            $test_student = new Student($student_name,$date_of_enrollment);
            $test_student->save();

            $student_name2 = "Charles Babbage";
            $date_of_enrollment2 = "2017-03-01";
            $test_student2 = new Student($student_name2,$date_of_enrollment2);
            $test_student2->save();

            $course_name = "Macro Economics";
            $course_number = "ECON101";
            $test_course = new Course($course_name, $course_number);
            $test_course->save();
            $test_course->addStudent($test_student->getStudentId());
            $test_course->addStudent($test_student2->getStudentId());

            // Act
            $result = $test_course->getClass();
            

            // Assert
            $this->assertEquals([$test_student, $test_student2], $result);
        }



    }
?>
