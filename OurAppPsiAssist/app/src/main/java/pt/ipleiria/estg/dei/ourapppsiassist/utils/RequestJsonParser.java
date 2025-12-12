package pt.ipleiria.estg.dei.ourapppsiassist.utils;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;

import com.android.volley.Request;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class RequestJsonParser {
    public static ArrayList<Request> parserJsonRequests(JSONArray response){
        ArrayList<Request> requests = new ArrayList<>();

        for(int i = 0; i<response.length(); i++){
            try {
                JSONObject auxRequest = (JSONObject) response.get(i);
                int id = auxRequest.getInt("id");
                int customer_id = auxRequest.getInt("customer_id");
                String title = auxRequest.getString("title");
                String status = auxRequest.getString("status");
                String description = auxRequest.getString("description");
                String created_at = auxRequest.getString("created_at");

                Request request = new Request(id, customer_id, title, status, description, created_at);
                request.add(request);

            } catch (JSONException e) {
                throw new RuntimeException(e);
            }
        }
        return requests;
    }

    public static Request parserJsonRequest(String response){

        Request request = null;

        try {
            //atenção a linha de baixo cast para Strin foi uma sugestão do IDE
            JSONObject auxRequest = new JSONObject(response);
            int id = auxRequest.getInt("id");
            int customer_id = auxRequest.getInt("customer_id");
            String title = auxRequest.getString("title");
            String status = auxRequest.getString("status");
            String description = auxRequest.getString("description");
            String created_at = auxRequest.getString("created_at");

            request = new Request(id, customer_id, title, status, description, created_at);

        } catch (JSONException e) {
            throw new RuntimeException(e);
        }

        return request;
    }

    public static String parserJsonLogin(){
        //TODO:

        return null;
    }

    public static boolean isConnectionInternet(Context context){
        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        if(cm!=null) {
            NetworkInfo ni = cm.getActiveNetworkInfo();
            return ni!=null && ni.isConnected();
        }
        return false;
    }

}
