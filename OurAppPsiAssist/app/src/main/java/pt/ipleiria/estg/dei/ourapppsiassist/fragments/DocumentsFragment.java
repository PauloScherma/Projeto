package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import androidx.lifecycle.ViewModelProvider;

import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import pt.ipleiria.estg.dei.ourapppsiassist.models.DocumentsViewModel;
import pt.ipleiria.estg.dei.ourapppsiassist.R;

public class DocumentsFragment extends Fragment {

    private DocumentsViewModel mViewModel;

    public static DocumentsFragment newInstance() {
        return new DocumentsFragment();
    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_documents, container, false);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        mViewModel = new ViewModelProvider(this).get(DocumentsViewModel.class);
        // TODO: Use the ViewModel
    }

}