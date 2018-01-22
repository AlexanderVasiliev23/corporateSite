<?php

namespace Corp\Http\Controllers;

use Mail;
use Corp\Menu;
use Illuminate\Http\Request;
use Corp\Repositories\MenusRepository;

class ContactsController extends SiteController
{
    /**
     * ContactsController constructor.
     */
    public function __construct()
    {
        parent::__construct(new MenusRepository(new Menu()));

        $this->bar = 'left';
        $this->template = env('THEME') . '.contacts';
    }

    /**
     * @param Request $request
     * @return $this
     * @throws \Throwable
     */
    public function index(Request $request)
    {
        if($request->isMethod('POST')) {

            $this->validate($request, [
                'name'  => 'required|max:255',
                'email' => 'required|email',
                'text'  => 'required'
            ]);

            $data = $request->all();

            Mail::send(env('THEME') . '.email', ['data' => $data], function ($m) use ($data) {
                $mail_admin = env('MAIL_ADMIN');

                $m->from($data['email'], $data['name']);
                $m->to($mail_admin, 'Mr. Admin')->subject('Question');
            });

            if(!Mail::failures()) {
                return redirect()->route('contacts')->with('status', 'Email is send');
            }

        }

        $this->title = 'Контакты';

        $content = view(env('THEME') . '.contact_content')->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $this->contentLeftBar = view(env('THEME') . '.contact_bar')->render();

        return $this->renderOutput();
    }
}
