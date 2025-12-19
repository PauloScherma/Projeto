package pt.ipleiria.estg.dei.ourapppsiassist.models;

import android.content.Context;
import android.widget.Toast;

import androidx.annotation.Nullable;

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

import pt.ipleiria.estg.dei.ourapppsiassist.activitys.RequestDetailsActivity;
import pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestListener;
import pt.ipleiria.estg.dei.ourapppsiassist.listeners.RequestsListener;
import pt.ipleiria.estg.dei.ourapppsiassist.utils.RequestJsonParser;

public class SingletonRequestManager {

    private static SingletonRequestManager instance;

    private static RequestQueue volleyQueue;
    private static ArrayList<Request> requests = new ArrayList<>();

    private final RequestBDHelper requestDB;

    private RequestsListener requestsListener;
    private RequestListener requestListener;

    // ---------------------------------------------------------
    // API PLACEHOLDERS
    // ---------------------------------------------------------
    private static final String mUrlAPIRequests = "YOUR_URL_HERE";
    private static final String TOKEN = "YOUR_TOKEN_HERE";
    // ---------------------------------------------------------

    public static synchronized SingletonRequestManager getInstance(Context context) {
        if (instance == null) {
            instance = new SingletonRequestManager(context);
        }

        if (volleyQueue == null) {
            volleyQueue = Volley.newRequestQueue(context.getApplicationContext());
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
    // Local DB
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

    public void addRequestsBD(ArrayList<Request> list) {
        requestDB.removeAllRequestsDB();
        requests.clear();

        for (Request r : list) {
            requestDB.addRequest(r);
            requests.add(r);
        }
    }

    public void editRequestBD(Request request) {
        if (requestDB.editRequest(request)) {
            for (int i = 0; i < requests.size(); i++) {
                if (requests.get(i).getId() == request.getId()) {
                    requests.set(i, request);
                    break;
                }
            }
        }
    }

    public void removeRequestBD(int id) {
        if (requestDB.removeRequest(id)) {
            requests.removeIf(r -> r.getId() == id);
        }
    }

    public void getAllRequests(Context context) {

    }

    public void editRequestAPI(Request request, RequestDetailsActivity requestDetailsActivity) {
    }

    public void addRequestAPI(Request request, RequestDetailsActivity requestDetailsActivity) {
    }

    public void removeRequestAPI(Request request, RequestDetailsActivity requestDetailsActivity) {

    }

    // ---------------------------------------------------------
    // API
    // ---------------------------------------------------------
//    public void addRequestAPI(final Request request, final Context context) {
//
//        if (!RequestJsonParser.isConnectionInternet(context)) {
//            Toast.makeText(context, "No internet connection", Toast.LENGTH_SHORT).show();
//            return;
//        }
//
//        StringRequest req = new StringRequest(Request.Method.POST, mUrlAPIRequests,
//                response -> {
//                    Request r = RequestJsonParser.parseJsonRequest(response);
//                    addRequestBD(r);
//
//                    if (requestListener != null) {
//                        requestListener.onUpdateRequest();
//                    }
//                },
//                error -> Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show()
//        ) {
//            @Nullable
//            @Override
//            protected Map<String, String> getParams() {
//                Map<String, String> params = new HashMap<>();
//                // params.put("token", TOKEN);
//                return params;
//            }
//        };
//
//        volleyQueue.add(req);
//    }
//
//    public void editRequestAPI(final Request request, final Context context) {
//
//        if (!RequestJsonParser.isConnectionInternet(context)) {
//            Toast.makeText(context, "No internet connection", Toast.LENGTH_SHORT).show();
//            return;
//        }
//
//        String url = mUrlAPIRequests + "/" + request.getId();
//
//        StringRequest req = new StringRequest(Request.Method.PUT, url,
//                response -> {
//                    editRequestBD(request);
//                    if (requestListener != null) {
//                        requestListener.onUpdateRequest();
//                    }
//                },
//                error -> Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show()
//        );
//
//        volleyQueue.add(req);
//    }
//
//    public void removeRequestAPI(final Request request, final Context context) {
//
//        if (!RequestJsonParser.isConnectionInternet(context)) {
//            Toast.makeText(context, "No internet connection", Toast.LENGTH_SHORT).show();
//            return;
//        }
//
//        String url = mUrlAPIRequests + "/" + request.getId();
//
//        StringRequest req = new StringRequest(Request.Method.DELETE, url,
//                response -> {
//                    removeRequestBD(request.getId());
//                    if (requestListener != null) {
//                        requestListener.onUpdateRequest();
//                    }
//                },
//                error -> Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show()
//        );
//
//        volleyQueue.add(req);
//    }
//
//    public void getAllRequestsAPI(final Context context) {
//
//        if (!RequestJsonParser.isConnectionInternet(context)) {
//            Toast.makeText(context, "No internet connection", Toast.LENGTH_SHORT).show();
//            return;
//        }
//
//        JsonArrayRequest req = new JsonArrayRequest(
//                Request.Method.GET,
//                mUrlAPIRequests,
//                null,
//                response -> {
//                    ArrayList<Request> list = RequestJsonParser.parserJsonRequests(response);
//                    addRequestsBD(list);
//
//                    if (requestsListener != null) {
//                        requestsListener.onRefreshRequests(list);
//                    }
//                },
//                error -> Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show()
//        );
//
//        volleyQueue.add(req);
//    }
}
