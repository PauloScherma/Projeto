package pt.ipleiria.estg.dei.ourapppsiassist.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListAdapter;
import android.widget.TextView;

import java.util.ArrayList;

import pt.ipleiria.estg.dei.ourapppsiassist.R;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;

public class RequestAdapter implements ListAdapter {

    private final Context context;
    private final LayoutInflater inflater;
    private final ArrayList<Request> requests;

    public RequestAdapter(Context context, ArrayList<Request> listRequest) {
        this.context = context;
        this.requests = listRequest;
        this.inflater = LayoutInflater.from(context);
    }

    @Override
    public int getCount() {
        return requests.size();
    }

    @Override
    public Object getItem(int position) {
        return requests.get(position);
    }

    @Override
    public long getItemId(int position) {
        return requests.get(position).getId();
    }

    @Override
    public int getItemViewType(int position) {
        return 0;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        ViewHolder holder;

        if (convertView == null) {
            convertView = inflater.inflate(R.layout.item_list_request, parent, false);
            holder = new ViewHolder(convertView);
            convertView.setTag(holder);
        } else {
            holder = (ViewHolder) convertView.getTag();
        }

        holder.update(requests.get(position));
        return convertView;
    }

    @Override
    public int getViewTypeCount() {
        return 0;
    }

    // Required ListAdapter methods
    @Override public boolean hasStableIds() { return true; }
    @Override public boolean isEmpty() { return requests.isEmpty(); }
    @Override public boolean areAllItemsEnabled() { return true; }
    @Override public boolean isEnabled(int position) { return true; }
    @Override public void registerDataSetObserver(android.database.DataSetObserver observer) {}
    @Override public void unregisterDataSetObserver(android.database.DataSetObserver observer) {}

    static class ViewHolder {
        TextView tvTitle, tvDescription, tvStatus;

        ViewHolder(View v) {
            tvTitle = v.findViewById(R.id.tvTitle);
            tvDescription = v.findViewById(R.id.tvDescription);
            tvStatus = v.findViewById(R.id.tvStatus);
        }

        void update(Request r) {
            tvTitle.setText(r.getTitle());
            tvDescription.setText(r.getDescription());
            tvStatus.setText(r.getStatus());
        }
    }
}
