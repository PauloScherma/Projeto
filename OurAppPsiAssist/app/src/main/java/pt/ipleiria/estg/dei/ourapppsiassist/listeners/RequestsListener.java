package pt.ipleiria.estg.dei.ourapppsiassist.listeners;

import java.util.ArrayList;
import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;

public interface RequestsListener {
    void onRefreshRequests(ArrayList<Request> requests);
    void onError(String message);
}
