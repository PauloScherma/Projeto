package pt.ipleiria.estg.dei.ourapppsiassist.models;

import android.content.Context;
import android.widget.Toast;

import androidx.annotation.Nullable;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestListener;
import pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestsListener;
import pt.ipleiria.estg.dei.ourapppsiassist.utils.RequestJsonParser;

public class SingletonRequestManager {

    private static SingletonRequestManager instance = null;

    private final RequestBDHelper requestDB;
    private static RequestQueue volleyQueue;

    private static ArrayList<Request> requests = new ArrayList<>();

    private RequestsListener requestsListener;
    private RequestListener requestListener;

    // ---------------------------------------------------------
    // API URL PLACEHOLDERS
    // ---------------------------------------------------------
    // TODO: Insert your API endpoints here
    // private static final String mUrlAPIRequests = "YOUR_URL_HERE";
    // private static final String TOKEN = "YOUR_TOKEN_HERE";
    // ---------------------------------------------------------

    public static synchronized SingletonRequestManager getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonRequestManager(context);
        }

        if (volleyQueue == null) {
            volleyQueue = Volley.newRequestQueue(context);
        }

        return instance;
    }

    private SingletonRequestManager(Context context) {
        requestDB = new RequestBDHelper(context);
        requests = requestDB.getAllRequestsDB();
    }

    // ---------------------------------------------------------
    // Listeners
    // ---------------------------------------------------------
    public void setRequestListener(RequestListener listener) {
        this.requestListener = listener;
    }

    public void setRequestsListener(RequestsListener listener) {
        this.requestsListener = listener;
    }

    // ---------------------------------------------------------
    // Local DB operations
    // ---------------------------------------------------------
    public ArrayList<Request> getRequests() {
        requests = requestDB.getAllRequestsDB();
        return new ArrayList<>(requests);
    }

    public static Request getRequest(int id) {
        for (Request r : requests) {
            if (r.getId() == id) return r;
        }
        return null;
    }

    public void addRequestBD(Request request) {
        Request inserted = requestDB.addRequest(request);

        if (inserted != null) {
            requests.add(inserted);
        }
    }

    public void addRequestsBD(ArrayList<Request> requestList) {
        requestDB.removeAllRequestsDB();
        requests.clear();

        for (Request r : requestList) {
            requestDB.addRequest(r);
            requests.add(r);
        }
    }

    public void editRequestBD(Request request) {
        boolean updated = requestDB.editRequest(request);

        if (updated) {
            for (int i = 0; i < requests.size(); i++) {
                if (requests.get(i).getId() == request.getId()) {
                    requests.set(i, request);
                    break;
                }
            }
        }
    }

    public void removeRequestBD(int idRequest) {
        boolean removed = requestDB.removeRequest(idRequest);

        if (removed) {
            Request toRemove = null;
            for (Request r : requests) {
                if (r.getId() == idRequest) {
                    toRemove = r;
                    break;
                }
            }
            if (toRemove != null) {
                requests.remove(toRemove);
            }
        }
    }

    // ---------------------------------------------------------
    // API Calls
    // ---------------------------------------------------------

    public void addRequestAPI(final Request request, final Context context) {

        if (!RequestJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, "No internet connection.", Toast.LENGTH_SHORT).show();
            return;
        }

        StringRequest req = new StringRequest(Request.Method.POST, mUrlAPIRequests,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Request newReq = RequestJsonParser.parseJsonRequest(response);
                        addRequestBD(newReq);

                        if (requestListener != null) {
                            requestListener.onUpdateRequest();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }
        ) {
            @Nullable
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();

                // TODO: Insert your fields
                // params.put("token", TOKEN);
                // params.put("title", request.getTitle());
                // params.put("description", request.getDescription());
                // params.put("status", request.getStatus());
                // params.put("customer_id", String.valueOf(request.getCustomer_id()));

                return params;
            }
        };

        volleyQueue.add(req);
    }

    public void editRequestAPI(final Request request, final Context context) {

        if (!RequestJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, "No internet connection.", Toast.LENGTH_SHORT).show();
            return;
        }

        String url = mUrlAPIRequests + "/" + request.getId();

        StringRequest req = new StringRequest(Request.Method.PUT, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        editRequestBD(request);

                        if (requestListener != null) {
                            requestListener.onRefreshDetalhes();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                }
        ) {
            @Nullable
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();

                // TODO: Insert fields to update

                return params;
            }
        };

        volleyQueue.add(req);
    }

    public void removeRequestAPI(final Request request, final Context context) {

        if (!RequestJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, "No internet connection.", Toast.LENGTH_SHORT).show();
            return;
        }

        String url = mUrlAPIRequests + "/" + request.getId();

        StringRequest req = new StringRequest(Request.Method.DELETE, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        removeRequestBD(request.getId());

                        if (requestListener != null) {
                            requestListener.onRefreshDetalhes();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });

        volleyQueue.add(req);
    }

    public void getAllRequestsAPI(final Context context) {

        if (!RequestJsonParser.isConnectionInternet(context)) {
            Toast.makeText(context, "No internet connection.", Toast.LENGTH_SHORT).show();
            return;
        }

        JsonArrayRequest req = new JsonArrayRequest(Request.Method.GET, mUrlAPIRequests, null,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        ArrayList<Request> list = RequestJsonParser.parserJsonRequests(response);
                        addRequestsBD(list);

                        if (requestsListener != null) {
                            requestsListener.onRefreshListRequests(list);
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });

        volleyQueue.add(req);
    }
}
