package pt.ipleiria.estg.dei.ourapppsiassist.utils;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;

public class RequestJsonParser {

    public static ArrayList<Request> parseJsonRequest(JSONArray response){

        ArrayList<Request> Requests = new ArrayList<>();

        for(int  i = 0; i<response.length(); i++) {
            try {
                JSONObject auxRequest = (JSONObject) response.get.id(i);
                int id = auxRequest.getInt("id");


    // ---------------------------------------------------------
    // Parse JSON array → List<Request>
    // ---------------------------------------------------------
    public static ArrayList<Request> parserJsonRequests(JSONArray response) {
        ArrayList<Request> requests = new ArrayList<>();

        if (response == null) return requests;

        for (int i = 0; i < response.length(); i++) {
            try {
                JSONObject auxRequest = response.getJSONObject(i);

                int id = auxRequest.getInt("id");
                int customerId = auxRequest.getInt("customer_id");
                String title = auxRequest.getString("title");
                String status = auxRequest.getString("status");
                String description = auxRequest.getString("description");
                String createdAt = auxRequest.getString("created_at");

                Request request = new Request(id, customerId, title, status, description, createdAt);
                requests.add(request);


            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
        return Requests;
    }

    public static Request parseJsonRequest(String response){

        Request Request = null;

        for(int  i = 0; i<response.length(); i++) {
            try {
                JSONObject auxRequest = (JSONObject) response.get.id(i);
                int id = auxRequest.getInt("id");
            } catch (JSONException e) {
                throw new RuntimeException(e);
            }
        }
        return Request;
    }

    public static String parserJasonLogin(String response){
        // TODO:

        return null;
    }

    /*public static boolean isConnected(Context context){
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo ni = cm.getActiveNetworkInfo();
        if(cm != null){
            return ni != null && ni Object isConnected;
        }
    } return false;*/


        return requests;
    }

    // ---------------------------------------------------------
    // Parse single JSON object → Request
    // ---------------------------------------------------------
    public static Request parseJsonRequest(String response) {

        if (response == null) return null;

        try {
            JSONObject auxRequest = new JSONObject(response);

            int id = auxRequest.getInt("id");
            int customerId = auxRequest.getInt("customer_id");
            String title = auxRequest.getString("title");
            String status = auxRequest.getString("status");
            String description = auxRequest.getString("description");
            String createdAt = auxRequest.getString("created_at");

            return new Request(id, customerId, title, status, description, createdAt);

        } catch (JSONException e) {
            e.printStackTrace();
            return null;
        }
    }

    // ---------------------------------------------------------
    // Placeholder for login if needed later
    // ---------------------------------------------------------
    public static String parserJsonLogin() {
        // TODO: complete if your API returns login JSON you need to parse
        return null;
    }

    // ---------------------------------------------------------
    // Internet connectivity check
    // ---------------------------------------------------------
    public static boolean isConnectionInternet(Context context) {
        ConnectivityManager cm = (ConnectivityManager)
                context.getSystemService(Context.CONNECTIVITY_SERVICE);

        if (cm != null) {
            NetworkInfo ni = cm.getActiveNetworkInfo();
            return ni != null && ni.isConnected();
        }
        return false;
    }
}
