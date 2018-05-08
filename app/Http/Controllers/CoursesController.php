<?php

namespace App\Http\Controllers;

use App\Course;
use App\Transformers\Courses\CourseTransformer;
use Dingo\Api\Routing\Helpers;

/**
 * Course resource representation.
 *
 * @Resource("Courses", uri="/courses")
 */

class CoursesController extends Controller
{
    use Helpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show all courses
     *
     * Get a JSON representation of all the registered users.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Transaction({
     *   @Request(headers={"Content-Type": "application/json","Authorization": "Bearer <token>"}),
     *   @Response(200, body={"data": {
     *     {"name": "aut","description": "Reiciendis doloremque."},
     *     {"name": "aut","description": "Reiciendis doloremque."}
     *   }}),
     *   @Response(400, body={"error": "Provided token is expired."}),
     *   @Response(400, body={"error": "Decoding token failed."})
     * })
     */
    public function index()
    {
        // return Course::all();

        $courses = Course::all();
        return $this->response->collection($courses, new CourseTransformer);
    }
}
