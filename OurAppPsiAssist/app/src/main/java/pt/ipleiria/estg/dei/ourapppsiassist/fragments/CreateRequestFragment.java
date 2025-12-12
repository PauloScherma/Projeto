package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class CreateRequestFragment extends Fragment {

   public CreateRequestFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_create_request, container, false);
        //createRequestTextView = view.findViewById(R.id.btnCreateRequests);
        return view;

    }



}