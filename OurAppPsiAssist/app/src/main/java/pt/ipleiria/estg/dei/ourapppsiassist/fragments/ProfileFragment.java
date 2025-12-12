package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import androidx.fragment.app.Fragment;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class ProfileFragment extends Fragment {

    private TextView profileTextView;

    private TextView tvUserName, tvEmail, txtName, tvPhoneNumber;

public ProfileFragment(){
    // Required empty public constructor
}


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_profile, container, false);
        //profileTextView = view.findViewById(R.id.btnProfile);
         return view;

    }
}
