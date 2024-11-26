<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\AgeLevel;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Coach;
use App\Models\CoachPlan;
use App\Models\ContactUs;
use App\Models\FAQs;
use App\Models\Gallery;
use App\Models\LessonProgram;
use App\Models\OurLocation;
use App\Models\ProgramDetails;
use App\Models\Programs;
use App\Models\VenueDetails;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LandingPageApiController extends Controller
{
    public function AboutUsList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $AboutUs = AboutUs::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();

            $total_record = $AboutUs->count();

            return response()->json(["total_record" => $total_record,
            "data" => $AboutUs->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->full_content,
                    'image' => $item->image,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            }), "message" => "AboutUs List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function BlogList(Request $request)
    {
        try {
            $page = $request->input('page', 1);
            $row_per_page = config("global.per_api_limit", 10);
            $start_index = ($page - 1) * $row_per_page;
            $id = $request->input('blog_id');

            if ($id) {
                $Blog = Blog::where('id', $id)->first();

                if ($Blog) {
                    $total_record = 1;
                    $data = [
                        [
                            'id' => $Blog->id,
                            'title' => $Blog->title,
                            'content' => $Blog->full_content,
                            'image' => $Blog->image,
                            'created_at' => $Blog->created_at,
                            'updated_at' => $Blog->updated_at,
                        ]
                    ];
                } else {
                    return response()->json(["data" => (object)[], "message" => "Response not found!", "status" => false], 404);
                }
            } else {
                $BlogQuery = Blog::orderBy('id', 'desc');
                $total_record = $BlogQuery->count();

                $Blog = $BlogQuery->limit($row_per_page)->offset($start_index)->get();
                $data = $Blog->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => $item->full_content,
                        'image' => $item->image,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                });
            }

            return response()->json([
                "total_record" => $total_record,
                "data" => $data,
                "message" => "Blog List fetched successfully!",
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }


    public function GalleryList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;
            $id = $request->input('venue_id');

            if ($id) {
                $galleryRecord = Gallery::where('galleries.venue_id', $id)
                    ->join('venues', 'galleries.venue_id', '=', 'venues.id')
                    ->select('galleries.*', 'venues.name as venue_name')
                    ->first();

                if ($galleryRecord) {
                    $galleryRecords = [$galleryRecord];
                } else {
                    return response()->json(["data" => (object)[], "message" => "Response not found!", "status" => false], 404);
                }
            } else {
                $galleryRecords = Gallery::join('venues', 'galleries.venue_id', '=', 'venues.id')
                    ->select('galleries.*', 'venues.name as venue_name')
                    ->limit($row_per_page)
                    ->offset($start_index)
                    ->orderBy('galleries.id', 'desc')
                    ->get();
            }

            $total_record = count($galleryRecords);

            return response()->json([
                "total_record" => $total_record,
                "data" => collect($galleryRecords)->map(function ($item) {
                    // Assuming images is a string, split it into an array if necessary
                    $images = is_string($item->images) ? explode(',', $item->images) : $item->images;
                    $fullImagePaths = array_map(function ($relativePath) {
                        return asset('gallery_image/' . $relativePath); // This assumes the relative path is correct
                    }, $images);

                    return [
                        'id' => $item->id,
                        'venue_id' => $item->venue_id,
                        'venue_name' => $item->venue_name,
                        'images' => $fullImagePaths,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }),
                "message" => "Gallery List retrieved successfully!",
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }


    public function VenueDetailsList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;
            $id = $request->input('venue_id');

            if ($id) {
                $venueDetailsQuery = VenueDetails::where('venue_details.id', $id)
                    ->join('venues', 'venue_details.venue_id', '=', 'venues.id')
                    ->select('venue_details.*', 'venues.name as venue_name', 'venues.address as venue_address')
                    ->first();
                $venueDetailsArray = $venueDetailsQuery ? [$venueDetailsQuery] : [];
            } else {
                $venueDetailsArray = VenueDetails::join('venues', 'venue_details.venue_id', '=', 'venues.id')
                    ->select('venue_details.*', 'venues.name as venue_name', 'venues.address as venue_address')
                    ->limit($row_per_page)
                    ->offset($start_index)
                    ->orderBy('venue_details.id', 'desc')
                    ->get();
            }
            $total_record = count($venueDetailsArray);

            return response()->json([
                "total_record" => $total_record,
                "data" => collect($venueDetailsArray)->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => $item->full_content,
                        'image' => $item->image,
                        'venue_name' => $item->venue_name,
                        'venue_address' => $item->venue_address,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }),
                "message" => "VenueDetails List get successfully!",
                "status" => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function VenueList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $Venues = Venues::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $Venues->count();

            return response()->json(["total_record" => $total_record, "data" => $Venues, "message" => "Venues List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function PlanList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $CoachPlan = CoachPlan::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $CoachPlan->count();

            return response()->json(["total_record" => $total_record, "data" => $CoachPlan, "message" => "CoachPlan List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function CoachList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;
            $id = $request->input('id');

            // Eager load the country relationship
            $CoachQuery = Coach::with('country')->orderBy('id', 'desc');
            $total_record = $CoachQuery->count();

            if ($id) {
                $Coach = $CoachQuery->where('id', $id)->first();
                if ($Coach) {
                    // Map the single coach data
                    $data = [
                        [
                            'id' => $Coach->id,
                            'name' => $Coach->name,
                            'designation' => $Coach->designation,
                            'about' => $Coach->about,
                            'certification' => $Coach->certification,
                            'image' => $Coach->image,
                            'country_id' => $Coach->country_id,
                            'country_name' => $Coach->country->name ?? null,
                            'country_flag' => $Coach->country->flag,
                            'created_at' => $Coach->created_at,
                            'updated_at' => $Coach->updated_at,
                        ]
                    ];
                } else {
                    return response()->json(["data" => (object)[], "message" => "Response not found!", "status" => false], 404);
                }
            } else {
                $Coaches = $CoachQuery->limit($row_per_page)->offset($start_index)->get();
                // Map the data to include the country_name
                $data = $Coaches->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'designation' => $item->designation,
                        'about' => $item->about,
                        'certification' => $item->certification,
                        'image' => $item->image,
                        'country_id' => $item->country_id,
                        'country_name' => $item->country->name ?? null,
                        'country_flag' => $item->country->flag,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                })->toArray();
            }

            return response()->json(["total_record" => $total_record, "data" => $data, "message" => "Coach List fetched successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function OurLocation(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $OurLocation = OurLocation::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $OurLocation->count();

            return response()->json(["total_record" => $total_record, "data" => $OurLocation, "message" => "OurLocation List get successfully!", "status" => true], 200);

        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function ProgramList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $Program = Programs::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $Program->count();

            return response()->json(["total_record" => $total_record, "data" => $Program, "message" => "Programs List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function ProgramDetailsList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;
            $id = $request->input('program_id');

            $program_detail = ProgramDetails::find($id);
            if($program_detail) {
                if ($id) {
                    $ProgramDetailsQuery = ProgramDetails::where('program_details.id', $id)
                        ->join('programs', 'program_details.program_id', '=', 'programs.id')
                        ->select('program_details.*', 'programs.name as program_name')
                        ->first();
                    $ProgramDetailsArray = $ProgramDetailsQuery ? [$ProgramDetailsQuery] : [];
                } else {
                    $ProgramDetailsArray = ProgramDetails::join('programs', 'program_details.program_id', '=', 'programs.id')
                        ->select('program_details.*', 'programs.name as program_name')
                        ->limit($row_per_page)
                        ->offset($start_index)
                        ->orderBy('program_details.id', 'desc')
                        ->get();
                }

                $total_record = count($ProgramDetailsArray);

                return response()->json([
                    "total_record" => $total_record,
                    "data" => collect($ProgramDetailsArray)->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'content' => $item->full_content,
                            'image' => $item->image,
                            'program_name' => $item->program_name, // Use the joined venue name
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                        ];
                    }),
                    "message" => "ProgramDetails List get successfully!",
                    "status" => true
                ], 200);
            } else {
                return response()->json(["data" => (object)[], "message" => "Response not found!", "status" => false], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function FAQsList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $FAQs = FAQs::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $FAQs->count();

            return response()->json(["total_record" => $total_record, "data" => $FAQs, "message" => "FAQs List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function AgeLevelList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $AgeLevel = AgeLevel::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $AgeLevel->count();

            return response()->json(["total_record" => $total_record, "data" => $AgeLevel, "message" => "AgeLevel List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function LessonProgramList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $LessonProgram = LessonProgram::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $LessonProgram->count();

            return response()->json(["total_record" => $total_record, "data" => $LessonProgram, "message" => "LessonProgram List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function AddContactUs(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string',
                'email' => 'required|email',
                'contact_no' => 'required|integer',
                'age_level' => 'required|exists:age_levels,id',
                'lesson_program' => 'required|exists:lesson_programs,id',
                'location' => 'required|exists:our_locations,id',
            ];

            $messages = [
                'age_level.exists' => 'Age Level Response Not Found!',
                'lesson_program.exists' => 'Lesson Program Not Found!',
                'location.exists' => 'Location Not Found!',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json(["data" => (object)[], "message" => $validator->errors()->first(), "status" => false], 400);
            }

            $ContactUs = new ContactUs();
            $ContactUs->name = $request->name;
            $ContactUs->email = $request->email;
            $ContactUs->contact_no = $request->contact_no;
            $ContactUs->age_level = $request->age_level;
            $ContactUs->lesson_program = $request->lesson_program;
            $ContactUs->location = $request->location;
            $ContactUs->massage = $request->massage;
            $ContactUs->save();

            return response()->json(["data" => (object)[], "message" => "Contact Add successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }

    public function BannerList(Request $request)
    {
        try {
            $page = $request->input('page') ?? 1;
            $row_per_page = config("global.per_api_limit") ?? 10;
            $start_index = ($page - 1) * $row_per_page;

            $Banner = Banner::limit($row_per_page)->offset($start_index)->orderBy('id', 'desc')->get();
            $total_record = $Banner->count();

            return response()->json(["total_record" => $total_record, "data" => $Banner, "message" => "Banner List get successfully!", "status" => true], 200);
        } catch (\Throwable $th) {
            return response()->json(["data" => (object)[], "message" => "Something went wrong!", "status" => false], 500);
        }
    }
}
