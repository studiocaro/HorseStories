<?php namespace HorseStories\Http\Requests;

use HorseStories\Http\Requests\Request;

class ConversationRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'subject' => 'required',
            'message' => 'required',
		];
	}

}
