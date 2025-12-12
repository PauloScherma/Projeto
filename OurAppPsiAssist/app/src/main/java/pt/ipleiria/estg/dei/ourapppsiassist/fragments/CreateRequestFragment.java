package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import androidx.lifecycle.ViewModelProvider;

import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import pt.ipleiria.estg.dei.ourapppsiassist.models.CreateRequestViewModel;
import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class CreateRequestFragment extends Fragment {

    private CreateRequestViewModel mViewModel;

    public static CreateRequestFragment newInstance() {
        return new CreateRequestFragment();
    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_create_request, container, false);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        mViewModel = new ViewModelProvider(this).get(CreateRequestViewModel.class);
        // TODO: Use the ViewModel
    }

    // here i want to create a frag for the purpose of creating new requests
    // as such i need a seter



}