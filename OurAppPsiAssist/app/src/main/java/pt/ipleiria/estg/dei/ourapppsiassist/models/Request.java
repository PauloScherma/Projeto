package pt.ipleiria.estg.dei.ourapppsiassist.models;
public class Request{
    private int id, customer_id;
    private String title;
    private String status;
    private String description;
    private String created_at;
    private String updated_at;
    public Request(int id, int customer_id, String title, String status, String description, String created_at, String updated_at) {
        this.id = id;
        this.customer_id = customer_id;
        this.title = title;
        this.status = status;
        this.description = description;
        this.created_at = created_at;
        this.updated_at = updated_at;
    }

    public static Object get(int i) {
        return null;
    }
    public static int size() {
        return 0;
    }
    public int getId()
    {
        return id;
    }
    public void setId(int id)
    {
        this.id = id;
    }
    public int getCustomer_id()
    {
        return customer_id;
    }
    public void setCustomer_id(int customer_id)
    {
        this.customer_id = customer_id;
    }
    public String getStatus()
    {
        return status;
    }
    public void setStatus(String status)
    {
        this.status = status;
    }
    public String getTitle()
    {
        return title;
    }
    public void setTitle(String title)
    {
        this.title = title;
    }
    public String getDescription()
    {
        return description;
    }
    public void setDescription(String description)
    {
        this.description = description;
    }
    public String getCreated_at()
    {
        return created_at;
    }
    public void setCreated_at(String created_at)
    {
        this.created_at = created_at;
    }
    public String getUpdated_at()
    {
        return updated_at;
    }
    public void setUpdated_at(String updated_at)
    {
        this.updated_at = updated_at;
    }

    public int getImgProfile() {
        return 0;
    }

    public String getCreatedBy() {
        return "";
    }

    public int getCreatedAt() {
        return 0;
    }
}
