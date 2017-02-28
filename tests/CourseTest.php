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
            // Student::deleteAll();
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



    }
?>
