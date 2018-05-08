<?php

namespace App\Transformers\Courses;

use App\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Course $course)
    {

        return [
            'name' => $course->name,
            'description' => $course->description,
        ];
    }
}
