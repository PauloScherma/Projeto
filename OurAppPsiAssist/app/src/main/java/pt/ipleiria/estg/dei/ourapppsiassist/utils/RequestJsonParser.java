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
/*
    public static ArrayList<Request> parseJsonRequest(JSONArray response){

        ArrayList<Request> Requests = new ArrayList<>();

        for(int  i = 0; i<response.length(); i++) {
            try {
                JSONObject auxRequest = (JSONObject) response.get.id(i);
                int id = auxRequest.getInt("id");
            } catch (JSONException e) {
                throw new RuntimeException(e);
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

}
