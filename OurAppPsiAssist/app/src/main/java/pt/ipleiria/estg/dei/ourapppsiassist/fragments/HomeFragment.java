package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class HomeFragment extends Fragment {

    private pt.ipleiria.estg.dei.ourapppsiassist.models.HomeViewModel mViewModel;
    private ProgressBar progressBar;
    private TextView dataText;

    public static HomeFragment newInstance() {
        return new HomeFragment();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        progressBar = view.findViewById(R.id.progressBar);
        dataText = view.findViewById(R.id.dataText);

        fetchData();
    }

    private void fetchData() {
        progressBar.setVisibility(View.VISIBLE);

        String url = "https://your-api.com/endpoint";

        RequestQueue queue = Volley.newRequestQueue(requireContext());

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(
                Request.Method.GET,
                url,
                null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        progressBar.setVisibility(View.GONE);

                        // Example: reading a field called "message"
                        String message = response.optString("message", "No message from API");
                        dataText.setText(message);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        progressBar.setVisibility(View.GONE);
                        dataText.setText("Error: " + error.getMessage());
                    }
                }
        );

        queue.add(jsonObjectRequest);
    }

}