package pt.ipleiria.estg.dei.ourapppsiassist.fragments;

import android.content.Intent;
import android.os.Bundle;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;
import android.widget.Toast;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.activitys.MenuMainActivity;
import pt.ipleiria.estg.dei.ourapppsiassist.activitys.RequestActivity;
import pt.ipleiria.estg.dei.ourapppsiassist.adapters.RequestAdapter;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;
import pt.ipleiria.estg.dei.ourapppsiassist.models.SingletonRequestManager;

public class RequestListFragment extends Fragment {
    private ListView lvRequests;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_request, container, false);
        setHasOptionsMenu(true);
        lvRequests = view.findViewById(R.id.lvRequests);

        //search requests from api => Singleton (register listener + request => pedido)
        //SingletonRequestManager.getInstance(getContext()).setRequestListener((RequestListener) this);
        //SingletonRequestManager.getInstance(getContext()).getAllRequests(getContext());

        lvRequests.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent intent = new Intent(getContext(), RequestActivity.class);
                intent.putExtra("IDREQUEST", (int) id);
                startActivityForResult(intent, MenuMainActivity.EDIT);
            }
        });

        FloatingActionButton fabAdd = view.findViewById(R.id.fabAdd);
        fabAdd.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getContext(), RequestActivity.class);
                startActivityForResult(intent, MenuMainActivity.ADD);
            }
        });

        return view;
    }

    @Override
    public void onCreateOptionsMenu(@NonNull Menu menu, @NonNull MenuInflater inflater) {
        super.onCreateOptionsMenu(menu, inflater);
        inflater.inflate(R.menu.menu_search,menu);

        MenuItem itemPesquisa = menu.findItem(R.id.itemSearch);
        SearchView searchView = (SearchView) itemPesquisa.getActionView();
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            //data model
            @Override
            public boolean onQueryTextChange(String s) {
                ArrayList<Request> tempRequests = new ArrayList<>();
                for(Request r: SingletonRequestManager.getInstance(getContext()).getRequests()){
                    if(r.getTitle().toLowerCase().contains(s.toLowerCase())){
                        tempRequests.add(r);
                    }
                    //update view
                    lvRequests.setAdapter(new RequestAdapter(getContext(),tempRequests));
                }
                return true;
            }
            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }
        });
    }


    @Override
    public void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        //super.onActivityResult(requestCode, resultCode, data);
        if(requestCode == MenuMainActivity.ADD || requestCode == MenuMainActivity.EDIT){
            if(resultCode == MenuMainActivity.RESULT_OK){
                //if detailsActivity.setResult(RESULT_OK)
                SingletonRequestManager.getInstance(getContext()).getAllRequests(getContext());

                switch(requestCode) {
                    case MenuMainActivity.ADD:
                        Toast.makeText(getContext(), "Request created", Toast.LENGTH_SHORT).show();
                        break;
                    case MenuMainActivity.EDIT:
                        Toast.makeText(getContext(), "Request edited", Toast.LENGTH_SHORT).show();
                        break;
                }
            }
        }
    }

    public void onRefreshListRequests(ArrayList<Request> listRequest) {
        if(listRequest!=null){
            lvRequests.setAdapter(new RequestAdapter(getContext(), listRequest));
        }
    }
}