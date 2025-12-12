package pt.ipleiria.estg.dei.ourapppsiassist.listeners;

import pt.ipleiria.estg.dei.ourapppsiassist.models.Request;

public interface RequestListener {
    void onUpdateRequest(Request request);
    void onError(String message);
}
