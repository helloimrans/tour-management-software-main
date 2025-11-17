<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\MailSendMail;
use App\Models\SendMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SendMailController extends Controller
{

    public function index()
    {

        if (request()->ajax()) {
            $data = SendMail::latest();

            if (request()->has('status') && !empty(request()->status)) {
                $data->where('status', request()->status);
            }

            if (request()->has('date') && !empty(request()->date)) {
                $data->whereDate('created_at', request()->date);
            }



            return DataTables::of($data)
                ->editColumn('status', function ($row) {
                    return $row->status ? 'Sent' : 'Failed';
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('m-d-Y h:ia');
                })
                ->addColumn('action', function ($row) {
                    return '';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $users = User::generalUser()->get();
        return view('admin.emails.send-mail', compact('users'));
    }
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'subject' => 'required|string',
            'body' => 'required|string',
        ])->validate();

        $users = User::generalUser();

        if ($request->has('specific_users')) {
            $users = $users->whereIn('id', $request->user_ids);
        }

        $users->chunk(50, function ($users) use ($validatedData) {
            foreach ($users as $user) {
                $status = false;

                try {
                    if (!empty($validatedData['subject']) && !empty($validatedData['body'])) {
                        Mail::to($user->email)->send(new MailSendMail($validatedData));
                        // SendEmailJob::dispatch($validatedData, $user, auth()->user()->id);
                        $status = true;
                    } else {
                        Log::warning('Email subject or body is null', [
                            'user_id' => $user->id,
                            'subject' => $validatedData['subject'],
                            'body' => $validatedData['body'],
                        ]);
                    }
                } catch (\Throwable $th) {
                    Log::error('Failed to send email', [
                        'user_id' => $user->id,
                        'error' => $th->getMessage(),
                    ]);
                }

                SendMail::create([
                    'from_email' => env('MAIL_USERNAME'),
                    'to_email' => $user->email,
                    'subject' => $validatedData['subject'] ?? '',
                    'body' => $validatedData['body'] ?? '',
                    'send_by' => auth()->user()->id,
                    'status' => $status,
                ]);
            }
        });

        return redirect()->back()->with([
            'message' => 'Messages sent successfully.',
            'alert-type' => 'success',
        ]);
    }

}
