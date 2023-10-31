<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *      path="/api/quotes/",
 *      operationId="getListOfQuotes",
 *      tags={"Quotes"},
 *      summary="Get list of your favorite Quotes",
 *      description="Returns a list of your favorite Kanye West Quotes",
 *      @OA\Response(
 *          response=200,
 *          description="List of Quotes objects based on your favorites",
 *          @OA\JsonContent()
 *      ),
 *      @OA\Response(
 *          response=400, 
 *          description="Bad request"
 *      ),
 *      @OA\Parameter(
 *          name="Content-Type",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          example="application/json",
 *          description="Content Type"
 *      ),
 *      security={
 *          {"bearerAuth": {}}
 *      },
 * )
 * @OA\Get(
 *      path="/api/random_quotes/{amount}",
 *      operationId="getListOfRandomQuotes",
 *      tags={"Quotes"},
 *      summary="Get list of Random Quotes",
 *      description="Returns list of random generated quotes",
 *      @OA\Parameter(
 *          name="amount",
 *          in="path",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          ),
 *          description="The number of random quotes to retrieve"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Array of random generated quotes",
 *          @OA\JsonContent()
 *      ),
 *      @OA\Response(
 *          response=400, 
 *          description="Bad request"
 *      ),
 *      @OA\Parameter(
 *          name="Content-Type",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          example="application/json",
 *          description="Content Type"
 *      ),
 *      security={
 *          {"bearerAuth": {}}
 *      },
 * )
 * @OA\Delete(
 *      path="/api/quote/{quoteId}",
 *      operationId="deleteQuoteFromFavorites",
 *      tags={"Quotes"},
 *      summary="Delete a Quote from your favorites.",
 *      description="Delete a Quote from your favorites list of Kanye West Quotes",
 *      @OA\Parameter(
 *          name="quoteId",
 *          in="path",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          ),
 *          description="The id of the Quote to delete"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Returns the deleted object",
 *          @OA\JsonContent()
 *      ),
 *      @OA\Response(
 *          response=400, 
 *          description="Bad request"
 *      ),
 *      @OA\Parameter(
 *          name="Content-Type",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          example="application/json",
 *          description="Content Type"
 *      ),
 *      security={
 *          {"bearerAuth": {}}
 *      },
 * )
 */


class QuoteController extends Controller
{




    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            $quotes = Quote::where("user_id", $request->user()->id)->get();
            return response()->json($quotes, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    public function randomQuotes(int $amount)
    {
        $quotes = [];
        $client = new Client();

        while (count($quotes) < $amount) {

            $response = $client->request('GET', 'https://api.kanye.rest/');
            $statusCode = $response->getStatusCode();
            if ($statusCode != 200) {
                return response()->json(["message" => "There has been an unexpected error generating the quotes."], $statusCode);
            }

            $quote = json_decode($response->getBody());
            if (in_array($quote, $quotes) == False) {
                array_push($quotes, $quote);
            }
        }

        return response()->json(["amount" => $amount, "quotes" => $quotes], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuoteRequest $request)
    {
        //return $request;
        try {
            $quote = Quote::create($request->all());
            return response()->json($quote, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuoteRequest  $request
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote, Request $request)
    {
        try {
            $user = $request->user();

            if($user->id != $quote->user_id) {
                return response()->json(["message"=> "You can't remove a Quote from someone else."],403);
            }
            
            $quote->delete();
            return response()->json($quote, 200);
            ;
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
